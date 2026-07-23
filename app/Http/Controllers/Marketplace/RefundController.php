<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function request(Request $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        // Only allow refund within 14 days and if order is delivered/completed
        abort_unless(
            in_array($order->status, ['delivered', 'completed'])
            && $order->updated_at->diffInDays(now()) <= 14,
            422,
            'La période de remboursement est expirée ou la commande ne peut pas être remboursée.'
        );

        Refund::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(),
            'amount'   => $order->total_price,
            'reason'   => $request->reason,
            'status'   => 'pending',
        ]);

        return back()->with('success', 'Votre demande de remboursement a été soumise.');
    }
}
