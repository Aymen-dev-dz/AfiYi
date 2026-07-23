<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundAdminController extends Controller
{
    public function process(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->total_price,
            'reason' => 'required|string|max:500',
        ]);

        Refund::create([
            'order_id'     => $order->id,
            'user_id'      => $order->user_id,
            'amount'       => $request->amount,
            'reason'       => $request->reason,
            'status'       => 'approved',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        $order->update(['status' => 'refunded']);

        return back()->with('success', 'Remboursement traité.');
    }
}
