<?php

namespace App\Services\Marketplace;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get the current session cart items as a simple array.
     */
    public function resolveCart(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Get a summary (total, item count, items collection).
     */
    public function getSummary(array $cart): array
    {
        $items = collect($cart);
        $subtotal = $items->sum(fn ($item) => $item['price'] * $item['quantity']);
        $coupon   = Session::get('coupon');
        $discount = $coupon ? round($subtotal * ($coupon['discount'] / 100), 2) : 0;
        $total    = max(0, $subtotal - $discount);

        return [
            'items'         => $items,
            'item_count'    => $items->sum('quantity'),
            'subtotal'      => $subtotal,
            'discount'      => $discount,
            'coupon'        => $coupon,
            'total'         => $total,
        ];
    }

    public function clear(array &$cart): void
    {
        Session::forget('cart');
    }
}
