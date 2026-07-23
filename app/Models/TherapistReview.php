<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapistReview extends Model
{
    protected $fillable = [
        'consultation_id', 'therapist_profile_id', 'patient_id',
        'rating', 'comment', 'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'rating'       => 'integer',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function therapistProfile(): BelongsTo
    {
        return $this->belongsTo(TherapistProfile::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
