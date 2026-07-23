<?php

namespace App\Services\Marketplace;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Create an Order from the cart. In full Stripe mode this would
     * create a PaymentIntent; for now we create a pending order and
     * simulate the checkout session URL.
     */
    public function initiateCheckout(array $cart, array $addresses, ?string $couponCode = null, string $paymentMethod = 'card'): array
    {
        $items    = collect($cart);
        $subtotal = $items->sum(fn ($item) => $item['price'] * $item['quantity']);
        $discount = 0;
        $total    = $subtotal - $discount;

        // Create order
        $order = Order::create([
            'reference'       => Order::generateReference(),
            'user_id'         => Auth::id(),
            'status'          => Order::STATUS_PENDING,
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'tax_amount'      => 0,
            'shipping_amount' => 0,
            'total_price'     => $total,
            'currency'        => 'DZD',
            'coupon_code'     => $couponCode,
            'notes'           => json_encode(['payment_method' => $paymentMethod]),
            'billing_address' => $addresses['billing'] ?? [],
            'shipping_address'=> $addresses['shipping'] ?? [],
        ]);

        // Create order items with destiny tokens
        foreach ($items as $item) {
            $destinyToken = null;
            
            // Generate token if it's a destiny product or physical product that includes it
            // For now, based on requirements, we generate it for all items or if they are in "Destiny" category.
            // Let's assume all items in this flow get a token for simplicity as requested, 
            // or maybe just check if product category is destiny. We'll generate it.
            $destinyToken = Str::uuid()->toString();

            $orderItem = OrderItem::create([
                'order_id'           => $order->id,
                'product_id'         => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'seller_id'          => $item['seller_id'],
                'product_name'       => $item['name'],
                'quantity'           => $item['quantity'],
                'unit_price'         => $item['price'],
                'subtotal'           => $item['price'] * $item['quantity'],
                'destiny_token'      => $destinyToken,
            ]);

            \App\Models\DestinyConnection::create([
                'user_id' => Auth::id(),
                'order_item_id' => $orderItem->id,
                'token' => $destinyToken,
                'status' => 'active',
                'expires_at' => now()->addYear(), // Expire after 1 year
            ]);
        }

        // In a real implementation, we'd create a Stripe PaymentIntent here.
        // For now we return a mock client_secret.
        return [
            'order'         => $order,
            'client_secret' => 'mock_pi_' . Str::random(20) . '_secret_' . Str::random(20),
        ];
    }
}
