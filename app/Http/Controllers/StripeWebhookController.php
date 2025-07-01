<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // 1. Verify the webhook signature
        try {
            $payload       = $request->getContent();
            $sigHeader     = $request->header('Stripe-Signature');
            $webhookSecret = config('services.stripe.webhook_secret');

            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $webhookSecret
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook verification failed: ' . $e->getMessage());
            return response('Invalid signature', Response::HTTP_BAD_REQUEST);
        }

        // 2. Handle the event
        try {
            DB::beginTransaction();

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;

                    // Validate required metadata
                    if (empty($paymentIntent->metadata->order_id)) {
                        throw new \Exception('Missing order_id in payment intent metadata');
                    }

                    $order = Order::where('status', 'pending')
                        ->find($paymentIntent->metadata->order_id);

                    if (! $order) {
                        throw new \Exception('Order not found or already processed');
                    }

                    $order->update([
                        'status'            => 'processing',
                        'stripe_payment_id' => $paymentIntent->id,
                    ]);

                    Log::info("Updated order {$order->id} to processing status");
                    break;

                // Add other event types as needed
                default:
                    Log::info("Unhandled event type: {$event->type}");
            }

            DB::commit();
            return response('Webhook handled', Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook handler failed: {$e->getMessage()}", [
                'event'     => $event->type ?? null,
                'exception' => $e,
            ]);
            return response('Error processing webhook', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
