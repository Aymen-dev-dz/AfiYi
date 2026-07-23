<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapistUnavailability extends Model
{
    protected $fillable = [
        'therapist_profile_id', 'start_date', 'end_date', 'reason', 'is_recurring',
    ];
    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'is_recurring' => 'boolean',
    ];

    public function therapistProfile(): BelongsTo
    {
        return $this->belongsTo(TherapistProfile::class);
    }
}
