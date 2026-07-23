<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Marketplace\CartService;
use App\Services\Marketplace\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService     $cartService,
        private readonly CheckoutService $checkoutService,
    ) {}

    public function index()
    {
        $cart    = $this->cartService->resolveCart();
        $summary = $this->cartService->getSummary($cart);

        if ($summary['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('marketplace.checkout.index', compact('cart', 'summary'));
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'billing.first_name'   => 'required|string|max:100',
            'billing.last_name'    => 'required|string|max:100',
            'billing.address_line1'=> 'required|string|max:255',
            'billing.postal_code'  => 'required|string|max:20',
            'billing.city'         => 'required|string|max:100',
            'billing.country'      => 'required|string|size:2',
            'shipping.address_line1' => 'required|string|max:255',
            'shipping.postal_code'   => 'required|string|max:20',
            'shipping.city'          => 'required|string|max:100',
            'shipping.country'       => 'required|string|size:2',
            'payment_method'         => 'required|string|in:card,cod',
        ]);

        $cart = $this->cartService->resolveCart();

        try {
            $result = $this->checkoutService->initiateCheckout(
                cart: $cart,
                addresses: [
                    'billing'  => $request->input('billing'),
                    'shipping' => $request->input('shipping'),
                ],
                couponCode: session('coupon_code'),
                paymentMethod: $request->input('payment_method', 'cod')
            );

            // Vider le panier après checkout initié
            $this->cartService->clear($cart);
            session()->forget('coupon_code');

            return response()->json([
                'client_secret' => $result['client_secret'],
                'order_id'      => $result['order']->id,
                'order_ref'     => $result['order']->reference,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (!$order->isPaid()) {
            $order->update([
                'status' => Order::STATUS_PROCESSING,
                'paid_at' => now(),
            ]);
        }

        $connectionUrl = route('destiny.lobby', ['token' => $order->reference]);
        
        $qrOptions = new \chillerlan\QRCode\QROptions([
            'version'      => 5,
            'outputType'   => \chillerlan\QRCode\Output\QRMarkupSVG::class,
            'eccLevel'     => \chillerlan\QRCode\Common\EccLevel::L,
            'addQuietzone' => true,
        ]);
        $qrCode = (new \chillerlan\QRCode\QRCode($qrOptions))->render($connectionUrl);

        return view('marketplace.checkout.success', compact('order', 'qrCode'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->update(['status' => Order::STATUS_CANCELLED]);
        return redirect()->route('cart.index')->with('info', 'Commande annulée.');
    }
}
