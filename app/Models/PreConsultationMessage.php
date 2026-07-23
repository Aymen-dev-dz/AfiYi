<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreConsultationMessage extends Model
{
    protected $fillable = [
        'consultation_id',
        'sender_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'message' => 'encrypted',
        'read_at' => 'datetime',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
