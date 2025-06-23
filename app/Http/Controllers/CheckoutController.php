<?php
namespace App\Http\Controllers;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function process(CheckoutRequest $request)
    {
        try {
            // Handle "bank" payment method
            if ($request->payment_method === 'bank') {
                return back()->with('error', 'Bank transfer payment method is coming soon!');
            }
            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->with('error', 'Your cart is empty.');
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
            // Create the order
            $order = Order::create($orderData);
            // Create order items
            foreach ($cart as $productId => $item) {
                $order->orderItems()->create([
                    'product_id'   => $productId,
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                ]);
            }
            // Find the admin or recipient(s) to notify
            $admin = User::where('is_admin', true)->first();
            if ($admin) {
                $admin->notify(new NewOrderNotification($order));
            }
            // Notify the customer if logged in and not admin
            if (Auth::check() && !Auth::user()->is_admin) {
                Auth::user()->notify(new NewOrderNotification($order));
            }
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Order placed successfully!');
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
}
