<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapistSchedule extends Model
{
    protected $fillable = [
        'therapist_profile_id', 'day_of_week', 'start_time', 'end_time', 'is_active',
    ];
    protected $casts = ['is_active' => 'boolean'];

    public function therapistProfile(): BelongsTo
    {
        return $this->belongsTo(TherapistProfile::class);
    }

    public function getDayNameAttribute(): string
    {
        return ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'][$this->day_of_week] ?? '';
    }
}
