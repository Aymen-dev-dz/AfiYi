<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        // Verify signature if secret is configured
        if ($secret) {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (\Exception $e) {
                Log::error('Stripe webhook signature failed: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } else {
            $event = json_decode($payload, true);
            $event = (object) ['type' => $event['type'] ?? '', 'data' => (object) ['object' => (object) ($event['data']['object'] ?? [])]];
        }

        match ($event->type) {
            'payment_intent.succeeded' => $this->handlePaymentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event->data->object),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentSucceeded(object $paymentIntent): void
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['status' => Order::STATUS_PROCESSING, 'paid_at' => now()]);
            Log::info("Order {$order->reference} marked as paid via Stripe webhook.");
        }
    }

    private function handlePaymentFailed(object $paymentIntent): void
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
            Log::warning("Order {$order->reference} cancelled due to payment failure.");
        }
    }
}
