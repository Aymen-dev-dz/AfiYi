<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seller_id', 'name', 'slug', 'description', 'short_description',
        'price', 'compare_price', 'cost_price', 'currency',
        'sku', 'barcode', 'track_inventory', 'quantity', 'low_stock_threshold',
        'category', 'tags', 'images', 'thumbnail',
        'status', 'is_featured', 'is_digital', 'weight', 'dimensions',
        'meta_title', 'meta_description', 'wellness_benefits'
    ];

    protected $casts = [
        'tags'       => 'array',
        'images'     => 'array',
        'dimensions' => 'array',
        'wellness_benefits' => 'array',
        'price'              => 'decimal:2',
        'compare_price'      => 'decimal:2',
        'cost_price'         => 'decimal:2',
        'track_inventory'    => 'boolean',
        'is_featured'        => 'boolean',
        'is_digital'         => 'boolean',
    ];

    const STATUS_DRAFT    = 'draft';
    const STATUS_ACTIVE   = 'active';
    const STATUS_ARCHIVED = 'archived';

    // ── Relationships ──────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeWithAnyTag($query, array $tags)
    {
        if (empty($tags)) return $query;
        
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return 'https://placehold.co/400x400/8b5cf6/ffffff?text=' . urlencode($this->name);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return null;
    }
}
