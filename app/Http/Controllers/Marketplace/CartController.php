<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Get the current cart from session.
     */
    private function getCart(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Save cart to session.
     */
    private function saveCart(array $cart): void
    {
        Session::put('cart', $cart);
    }

    public function index()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('marketplace.cart', compact('cart', 'total'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
            'variant_id' => 'nullable|integer',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $cart    = $this->getCart();
        $key     = $request->product_id . '-' . ($request->variant_id ?? 'default');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'seller_id'  => $product->seller_id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'thumbnail'  => $product->thumbnail_url,
                'quantity'   => $request->quantity,
            ];
        }

        $this->saveCart($cart);

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function updateItem(Request $request, string $item)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:99']);

        $cart = $this->getCart();

        if ($request->quantity === 0) {
            unset($cart[$item]);
        } else {
            $cart[$item]['quantity'] = $request->quantity;
        }

        $this->saveCart($cart);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function removeItem(string $item)
    {
        $cart = $this->getCart();
        unset($cart[$item]);
        $this->saveCart($cart);

        return back()->with('success', 'Article supprimé du panier.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon' => 'required|string']);

        // Simple stub — real logic would check a coupons table
        Session::put('coupon', ['code' => strtoupper($request->coupon), 'discount' => 10]);

        return back()->with('success', 'Code promo appliqué (-10%).');
    }

    public function removeCoupon()
    {
        Session::forget('coupon');

        return back()->with('success', 'Code promo retiré.');
    }
}
