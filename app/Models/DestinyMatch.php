<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinyMatch extends Model
{
    protected $fillable = [
        'uuid',
        'user_a_id',
        'user_b_id',
        'status',
        'match_mode',
        'role',
        'duration',
        'mood_score',
        'topic',
        'user_a_nickname',
        'user_b_nickname',
        'started_at',
        'closed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

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
        return $this->hasMany(AnonymousMessage::class, 'anonymous_room_id'); // We'll map this for now
    }
}
