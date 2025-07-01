<?php

// app/Services/StripePaymentService.php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripePaymentService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function processPayment(Order $order, string $paymentMethod)
    {
        try {
            $payment = $order->user->charge($order->total * 100, $paymentMethod, [
                'description' => 'Order #'.$order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ],
                'receipt_email' => $order->billing_email,
            ]);

            $order->update([
                'status' => 'processing',
                'stripe_payment_id' => $payment->id,
            ]);

            return $payment;
        } catch (IncompletePayment $e) {
            throw $e;
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment error: '.$e->getMessage());
            throw new \Exception('Payment processing failed: '.$e->getMessage());
        }
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        if (empty($paymentIntent->metadata->order_id)) {
            throw new \Exception('Missing order_id in metadata');
        }

        $order = Order::where('status', 'pending')
            ->find($paymentIntent->metadata->order_id);

        if (! $order) {
            throw new \Exception("Order not found: {$paymentIntent->metadata->order_id}");
        }

        $order->update([
            'status' => 'processing',
            'stripe_payment_id' => $paymentIntent->id,
        ]);

        Log::info("Updated order {$order->id} via webhook");
    }
}
