<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AnonymousRoom extends Model
{
    protected $fillable = [
        'uuid',
        'status', // waiting, active, closed
        'topic',
        'user_a_id',
        'user_b_id',
        'user_a_mood',
        'user_b_mood',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function userA()
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB()
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    public function messages()
    {
        return $this->hasMany(AnonymousMessage::class);
    }
}
