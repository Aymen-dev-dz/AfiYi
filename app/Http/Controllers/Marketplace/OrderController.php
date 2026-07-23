<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ── Client ─────────────────────────────────────────────────────

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items', 'payment'])
            ->latest()
            ->paginate(15);

        return view('marketplace.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items.product', 'items.variant', 'statusHistories', 'payment', 'refunds', 'shipments.events']);
        return view('marketplace.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->delete();
        return back()->with('success', 'Historique de commande supprimé.')->with('active_tab', 'journal');
    }

    // ── Vendeur ────────────────────────────────────────────────────

    public function sellerIndex()
    {
        $orders = Order::forSeller(Auth::id())
            ->with(['items' => fn ($q) => $q->where('seller_id', Auth::id()), 'user'])
            ->latest()
            ->paginate(20);

        return view('seller.orders.index', compact('orders'));
    }

    public function sellerShow(Order $order)
    {
        abort_unless(
            $order->items()->where('seller_id', Auth::id())->exists(),
            403
        );
        $order->load(['items' => fn ($q) => $q->where('seller_id', Auth::id()), 'user', 'shipments']);
        return view('seller.orders.show', compact('order'));
    }

    // ── Admin ──────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $orders = Order::with(['user', 'items', 'payment'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('reference', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function adminShow(Order $order)
    {
        $order->load(['items.product', 'user', 'payment', 'refunds', 'shipments', 'statusHistories', 'commissions.seller']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'  => 'required|string|in:processing,shipped,delivered,completed,cancelled',
            'comment' => 'nullable|string|max:500',
        ]);

        $from = $order->status;
        $order->update(['status' => $request->status]);

        \App\Models\OrderStatusHistory::create([
            'order_id'   => $order->id,
            'user_id'    => Auth::id(),
            'from_status'=> $from,
            'to_status'  => $request->status,
            'comment'    => $request->comment ?? 'Statut mis à jour par l\'administration.',
        ]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
