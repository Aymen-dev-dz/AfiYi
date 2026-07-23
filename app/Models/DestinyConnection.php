<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinyConnection extends Model
{
    protected $fillable = [
        'user_id',
        'order_item_id',
        'token',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
