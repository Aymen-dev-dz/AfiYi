<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Only seller of items in this order can create shipment
        abort_unless(
            $order->items()->where('seller_id', Auth::id())->exists(),
            403
        );

        $request->validate([
            'carrier'        => 'required|string|max:100',
            'tracking_number'=> 'nullable|string|max:100',
            'tracking_url'   => 'nullable|url|max:500',
            'notes'          => 'nullable|string|max:500',
        ]);

        $shipment = Shipment::create([
            'order_id'       => $order->id,
            'seller_id'      => Auth::id(),
            'carrier'        => $request->carrier,
            'tracking_number'=> $request->tracking_number,
            'tracking_url'   => $request->tracking_url,
            'status'         => 'shipped',
            'shipped_at'     => now(),
            'notes'          => $request->notes,
        ]);

        ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status'      => 'shipped',
            'description' => "Colis expédié via {$request->carrier}.",
            'occurred_at' => now(),
        ]);

        $order->update(['status' => 'shipped']);

        return back()->with('success', 'Expédition enregistrée.');
    }

    public function update(Request $request, Shipment $shipment)
    {
        abort_unless($shipment->seller_id === Auth::id(), 403);

        $request->validate([
            'status'      => 'required|in:in_transit,out_for_delivery,delivered,failed',
            'location'    => 'nullable|string|max:200',
            'description' => 'nullable|string|max:500',
        ]);

        $shipment->update(['status' => $request->status]);

        ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status'      => $request->status,
            'location'    => $request->location,
            'description' => $request->description ?? ucfirst(str_replace('_', ' ', $request->status)),
            'occurred_at' => now(),
        ]);

        if ($request->status === 'delivered') {
            $shipment->update(['delivered_at' => now()]);
            $shipment->order->update(['status' => 'delivered']);
        }

        return back()->with('success', 'Statut d\'expédition mis à jour.');
    }
}
