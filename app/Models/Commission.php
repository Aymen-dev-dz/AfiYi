<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    protected $fillable = [
        'order_id', 'order_item_id', 'seller_id',
        'gross_amount', 'commission_rate', 'commission_amount', 'net_amount',
        'status', 'paid_at',
    ];

    protected $casts = [
        'gross_amount'      => 'decimal:2',
        'commission_rate'   => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'net_amount'        => 'decimal:2',
        'paid_at'           => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
