<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnonymousChatRating extends Model
{
    protected $table = 'anonymous_chat_ratings';

    protected $fillable = [
        'match_id',
        'rated_user_id',
        'rater_user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * The match this rating belongs to.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(DestinyMatch::class, 'match_id');
    }

    /**
     * The user who was rated.
     */
    public function ratedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

    /**
     * The user who submitted the rating.
     */
    public function raterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rater_user_id');
    }
}
