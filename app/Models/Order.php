<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'user_id', 'status', 'subtotal', 'discount_amount',
        'tax_amount', 'shipping_amount', 'total_price', 'currency',
        'coupon_code', 'notes', 'billing_address', 'shipping_address',
        'payment_intent_id', 'paid_at',
    ];

    protected $casts = [
        'billing_address'  => 'array',
        'shipping_address' => 'array',
        'subtotal'         => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'tax_amount'       => 'decimal:2',
        'shipping_amount'  => 'decimal:2',
        'total_price'      => 'decimal:2',
        'paid_at'          => 'datetime',
    ];

    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED    = 'shipped';
    const STATUS_DELIVERED  = 'delivered';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_REFUNDED   = 'refunded';

    // ── Relationships ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public static function generateReference(): string
    {
        do {
            $ref = 'ORD-' . strtoupper(substr(md5(uniqid('', true)), 0, 8));
        } while (static::where('reference', $ref)->exists());
        return $ref;
    }

    public function isPaid(): bool
    {
        return !is_null($this->paid_at);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->whereHas('items', fn ($q) => $q->where('seller_id', $sellerId));
    }
}
