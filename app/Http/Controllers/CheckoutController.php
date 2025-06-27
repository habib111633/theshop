<?php
namespace App\Http\Controllers;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Exceptions\IncompletePayment;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function process(CheckoutRequest $request)
    {
        try {
            // Handle "bank" payment method (now Stripe)
            if ($request->payment_method === 'bank') {
                // Store checkout data in session for Stripe payment
                session([
                    'checkout_data' => $request->only([
                        'billing_name', 'billing_email', 'billing_address', 'billing_city',
                        'billing_state', 'billing_zip', 'billing_phone', 'shipping_address',
                        'shipping_city', 'shipping_state', 'shipping_zip'
                    ])
                ]);
                return redirect()->route('checkout.stripe');
            }
            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->with('error', 'Your cart is empty.');
            }

            // Stock validation for all cart items
            foreach ($cart as $productId => $item) {
                $product = \App\Models\Product::findOrFail($productId);
                if ($item['quantity'] > $product->stock) {
                    return back()->with('error', 'Not enough stock for ' . $product->name . '. Only ' . $product->stock . ' left.');
                }
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $taxRate = $request->payment_method === 'bank' ? 0.04 : 0.17;
            $tax = round($subtotal * $taxRate, 2);
            $shipping = 0.00;
            $total = $subtotal + $tax + $shipping;
            // Prepare order data
            $orderData = $request->only([
                'billing_name',
                'billing_email',
                'billing_city',
                'billing_state',
                'billing_zip',
                'billing_phone',
                'payment_method',
                'shipping_zip',
                'shipping_address',
            ]);
            $orderData['subtotal'] = $subtotal;
            $orderData['tax'] = $tax;
            $orderData['shipping'] = $shipping;
            $orderData['total'] = $total;
            $orderData['user_id'] = auth()->id();

            // Use a DB transaction for atomicity
            $order = null;
            DB::transaction(function () use ($orderData, $cart, &$order) {
                $order = \App\Models\Order::create($orderData);
                foreach ($cart as $productId => $item) {
                    $product = \App\Models\Product::findOrFail($productId);
                    // Double-check stock inside transaction
                    if ($item['quantity'] > $product->stock) {
                        throw new \Exception('Not enough stock for ' . $product->name . '. Only ' . $product->stock . ' left.');
                    }
                    $product->decrement('stock', $item['quantity']);
                    $order->orderItems()->create([
                        'product_id'   => $productId,
                        'product_name' => $item['name'],
                        'price'        => $item['price'],
                        'quantity'     => $item['quantity'],
                    ]);
                }
                // Optionally: notify user/admin here
                // $order->user->notify(new NewOrderNotification($order));
            });

            session()->forget('cart');
            // Redirect to thank you page with order ID
            return redirect()->route('checkout.thankyou', ['order' => $order->id])
                ->with('order', $orderData);
        } catch (\Throwable $e) {
            \Log::error('Order processing failed: '.$e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
    public function show(Request $request)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('dashboard');
        }
        return view('checkout');
    }
    // Add this new method
    public function thankyou(Request $request, $order)
    {
        $order = Order::findOrFail($order);
        return view('checkout.thankyou', compact('order'));
    }
    public function stripe(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty.');
        }
        // Calculate totals (reuse logic from process)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxRate = 0.04;
        $tax = round($subtotal * $taxRate, 2);
        $shipping = 0.00;
        $total = $subtotal + $tax + $shipping;

        // Create payment intent using Cashier
        $user = auth()->user();
        $paymentIntent = $user->createSetupIntent();

        return view('checkout.stripe', compact('cart', 'subtotal', 'tax', 'shipping', 'total', 'paymentIntent'));
    }
    public function stripeConfirm(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'billing_name' => 'required|string',
            'billing_email' => 'required|email',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_zip' => 'required|string',
            'billing_phone' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxRate = 0.04;
        $tax = round($subtotal * $taxRate, 2);
        $shipping = 0.00;
        $total = $subtotal + $tax + $shipping;

        try {
            $user = auth()->user();
            
            // Charge the user using Cashier
            $payment = $user->charge($total * 100, $request->payment_method, [
                'description' => 'Order payment',
                'metadata' => [
                    'user_id' => $user->id,
                    'billing_email' => $request->billing_email,
                ],
                'receipt_email' => $request->billing_email,
            ]);

            // Payment successful, create order
            $orderData = $request->only([
                'billing_name', 'billing_email', 'billing_city', 'billing_state', 'billing_zip', 'billing_phone',
            ]);
            $orderData['payment_method'] = 'stripe';
            $orderData['subtotal'] = $subtotal;
            $orderData['tax'] = $tax;
            $orderData['shipping'] = $shipping;
            $orderData['total'] = $total;
            $orderData['user_id'] = $user->id;
            $orderData['status'] = 'processing';
            $orderData['stripe_payment_id'] = $payment->id;

            $order = null;
            DB::transaction(function () use ($orderData, $cart, &$order) {
                $order = \App\Models\Order::create($orderData);
                foreach ($cart as $productId => $item) {
                    $product = \App\Models\Product::findOrFail($productId);
                    if ($item['quantity'] > $product->stock) {
                        throw new \Exception('Not enough stock for ' . $product->name . '. Only ' . $product->stock . ' left.');
                    }
                    $product->decrement('stock', $item['quantity']);
                    $order->orderItems()->create([
                        'product_id'   => $productId,
                        'product_name' => $item['name'],
                        'price'        => $item['price'],
                        'quantity'     => $item['quantity'],
                    ]);
                }
            });

            session()->forget('cart');
            session()->forget('checkout_data');
            
            return response()->json(['success' => true, 'redirect' => route('checkout.thankyou', ['order' => $order->id])]);
        } catch (IncompletePayment $exception) {
            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $exception->payment->client_secret()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

