<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function process(CheckoutRequest $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            if ($request->payment_method === 'bank') {
                return $this->prepareStripeCheckout($request);
            }

            $order = $this->createOrder($request, $cart);
            session()->forget('cart');

            return redirect()->route('checkout.thankyou', $order->id);
        } catch (\Throwable $e) {
            Log::error('Checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    public function stripeConfirm(Request $request, StripePaymentService $paymentService)
    {
        try {
            $request->validate($this->getPaymentValidationRules());
            $cart = session('cart', []);

            $order = $this->createOrder($request, $cart, 'stripe');
            $minimum = 0.50; // USD, adjust for your currency if needed

            if ($order->total < $minimum) {
                return response()->json(['error' => 'Order total must be at least $0.50'], 422);
            }

            $result = $paymentService->processPayment($order, $request->payment_method);
            session()->forget(['cart', 'checkout_data']);

            return response()->json([
                'success' => true,
                'redirect' => route('checkout.thankyou', $order->id)
            ]);
        } catch (IncompletePayment $e) {
            return $this->handle3DSecure($e, $order);
        } catch (\Throwable $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 422);
        }
    }

    public function show(Request $request)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('dashboard');
        }
        return view('checkout');
    }

    public function thankyou(Request $request, Order $order)
    {
        return view('checkout.thankyou', compact('order'));
    }

    public function stripe(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty.');
        }

        $totals = $this->calculateTotals($cart, 0.05);
        $user = auth()->user();

        return view('checkout.stripe', [
            ...$totals,
            'cart' => $cart,
            'paymentIntent' => $user->createSetupIntent(),
        ]);
    }

    private function prepareStripeCheckout(Request $request)
    {
        session(['checkout_data' => $request->only([
            'billing_name', 'billing_email', 'billing_address', 'billing_city',
            'billing_state', 'billing_zip', 'billing_phone', 'shipping_address',
            'shipping_city', 'shipping_state', 'shipping_zip',
        ])]);

        return redirect()->route('checkout.stripe');
    }

    private function createOrder(Request $request, array $cart, string $method = null): Order
    {
        $this->validateStock($cart);

        return DB::transaction(function () use ($request, $cart, $method) {
            $paymentMethod = $method ?: $request->payment_method;
            $orderData = $this->buildOrderData($request, $cart, $paymentMethod);
            $order = Order::create($orderData);

            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);
                $product->decrement('stock', $item['quantity']);

                $order->orderItems()->create([
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order;
        });
    }

    private function buildOrderData(Request $request, array $cart, string $paymentMethod): array
    {
        $taxRate = $paymentMethod === 'bank' ? 0.05 : 0.17;
        $totals = $this->calculateTotals($cart, $taxRate);

        return [
            ...$request->only([
                'billing_name', 'billing_email', 'billing_city',
                'billing_state', 'billing_zip', 'billing_phone',
                'shipping_zip', 'shipping_address',
            ]),
            'payment_method' => $paymentMethod,
            'user_id' => auth()->id(),
            ...$totals,
            'status' => 'pending',
        ];
    }

    private function calculateTotals(array $cart, float $taxRate): array
    {
        $subtotal = array_reduce($cart, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);
        $tax = round($subtotal * $taxRate, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => 0.00,
            'total' => $subtotal + $tax,
        ];
    }

    private function validateStock(array $cart): void
    {
        foreach ($cart as $productId => $item) {
            $product = Product::findOrFail($productId);
            if ($item['quantity'] > $product->stock) {
                throw new \Exception("Not enough stock for {$product->name}");
            }
        }
    }

    private function getPaymentValidationRules(): array
    {
        return [
            'payment_method' => 'required|string',
            'billing_name' => 'required|string',
            'billing_email' => 'required|email',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_zip' => 'required|string',
            'billing_phone' => 'required|string',
        ];
    }

    private function handle3DSecure(IncompletePayment $e, Order $order): JsonResponse
    {
        Log::info('3D Secure required', ['order_id' => $order->id]);
        session()->forget(['cart', 'checkout_data']);

        return response()->json([
            'requires_action' => true,
            'payment_intent_client_secret' => $e->payment->clientSecret(),
            'message' => '3D Secure authentication required',
            'redirect' => route('checkout.thankyou', $order->id),
        ]);
    }
}
