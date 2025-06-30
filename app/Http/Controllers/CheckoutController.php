<?php
namespace App\Http\Controllers;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\PaymentIntent;

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
            $taxRate = $request->payment_method === 'bank' ? 0.05 : 0.17;
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
        $taxRate = 0.05;
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
        try {
            // Validate the request
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

            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $taxRate = 0.05;
            $tax = round($subtotal * $taxRate, 2);
            $shipping = 0.00;
            $total = $subtotal + $tax + $shipping;

            $user = auth()->user();

            // Log the payment attempt
            \Log::info('Stripe payment attempt', [
                'user_id' => $user->id,
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'cart_items' => count($cart)
            ]);

            // Create order FIRST (with pending status)
            $orderData = $request->only([
                'billing_name', 'billing_email', 'billing_city', 'billing_state', 'billing_zip', 'billing_phone',
            ]);
            $orderData['payment_method'] = 'stripe';
            $orderData['subtotal'] = $subtotal;
            $orderData['tax'] = $tax;
            $orderData['shipping'] = $shipping;
            $orderData['total'] = $total;
            $orderData['user_id'] = $user->id;
            $orderData['status'] = 'pending'; // Start with pending status

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

            // Now try to charge the user
            $payment = $user->charge($total * 100, $request->payment_method, [
                'description' => 'Order payment',
                'metadata' => [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'billing_email' => $request->billing_email,
                ],
                'receipt_email' => $request->billing_email,
            ]);

            // Payment successful, update order status
            $order->update([
                'status' => 'processing',
                'stripe_payment_id' => $payment->id
            ]);

            // Log successful payment
            \Log::info('Stripe payment successful', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $total
            ]);

            session()->forget('cart');
            session()->forget('checkout_data');

            return response()->json(['success' => true, 'redirect' => route('checkout.thankyou', ['order' => $order->id])]);

        } catch (IncompletePayment $exception) {
            // Log 3D Secure requirement
            \Log::info('3D Secure authentication required', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'status' => $exception->payment->status
            ]);

            // Clear cart and checkout data since order is created
            session()->forget('cart');
            session()->forget('checkout_data');

            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $exception->payment->clientSecret(),
                'message' => '3D Secure authentication required',
                'redirect' => route('checkout.thankyou', ['order' => $order->id])
            ]);

        } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
            // Alternative way to catch IncompletePayment
            \Log::info('3D Secure authentication required (alternative)', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'status' => $exception->payment->status ?? 'unknown'
            ]);

            // Clear cart and checkout data since order is created
            session()->forget('cart');
            session()->forget('checkout_data');

            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $exception->payment->clientSecret(),
                'message' => '3D Secure authentication required',
                'redirect' => route('checkout.thankyou', ['order' => $order->id])
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            // Handle Stripe card errors
            \Log::error('Stripe card error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

            return response()->json([
                'error' => 'Card error: ' . $e->getMessage()
            ], 422);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle Stripe invalid request errors
            \Log::error('Stripe invalid request error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'param' => $e->getStripeParam()
            ]);

            return response()->json([
                'error' => 'Invalid payment request: ' . $e->getMessage()
            ], 422);

        } catch (\Exception $e) {
            // Handle any other errors
            \Log::error('Stripe payment error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Payment failed: ' . $e->getMessage()
            ], 422);
        }
    }
}

