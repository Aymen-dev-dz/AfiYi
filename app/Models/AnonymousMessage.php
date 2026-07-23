<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AnonymousMessage extends Model
{
    protected $fillable = [
        'anonymous_room_id',
        'sender_id',
        'message',
        'audio_path',
        'reactions',
    ];

    protected $casts = [
        'reactions' => 'array',
    ];

    public function room()
    {
        return $this->belongsTo(AnonymousRoom::class, 'anonymous_room_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Encrypt content on save
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = Crypt::encryptString($value);
    }

    // Decrypt content on read
    public function getMessageAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }
}
