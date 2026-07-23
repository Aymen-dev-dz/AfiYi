<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_variant_id', 'seller_id',
        'product_name', 'variant_label', 'sku',
        'quantity', 'unit_price', 'subtotal',
        'product_snapshot', 'fulfillment_status', 'destiny_token'
    ];

    protected $casts = [
        'product_snapshot' => 'array',
        'unit_price'       => 'decimal:2',
        'subtotal'         => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function commission(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Commission::class);
    }
}
