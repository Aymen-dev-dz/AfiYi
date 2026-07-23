<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    protected $fillable = [
        'user_id',
        'mood_score',
        'emotion',
        'stress_level',
        'sleep_quality',
        'energy_level',
        'wellness_score',
        'note',
        'social_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
