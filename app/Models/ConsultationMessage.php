<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationMessage extends Model
{
    protected $fillable = [
        'consultation_id',
        'sender_id',
        'message',
        'is_system',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            // Automatically encrypt/decrypt message content in the database
            'message' => 'encrypted',
            'is_system' => 'boolean',
        ];
    }

    /**
     * Get the consultation this message belongs to.
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Get the user who sent the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
