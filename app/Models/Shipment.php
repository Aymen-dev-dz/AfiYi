<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'seller_id', 'carrier', 'tracking_number',
        'tracking_url', 'status', 'shipped_at', 'delivered_at',
        'estimated_delivery_at', 'notes',
    ];

    protected $casts = [
        'shipped_at'           => 'datetime',
        'delivered_at'         => 'datetime',
        'estimated_delivery_at'=> 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class)->latest();
    }
}
