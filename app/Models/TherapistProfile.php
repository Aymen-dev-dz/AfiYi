<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TherapistProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'license_number', 'license_issuer', 'status',
        'specialties', 'languages', 'approaches', 'bio', 'photo',
        'experience_years', 'session_price', 'session_duration_minutes', 'currency',
        'max_clients', 'accepts_new_clients', 'offers_first_free_session',
        'rating', 'total_sessions', 'total_reviews',
        'daily_room_prefix', 'stripe_account_id',
        'stripe_onboarding_complete', 'stripe_charges_enabled', 'stripe_payouts_enabled',
        'verified_at',
    ];

    protected $casts = [
        'specialties'                => 'array',
        'languages'                  => 'array',
        'approaches'                 => 'array',
        'session_price'              => 'decimal:2',
        'rating'                     => 'decimal:2',
        'accepts_new_clients'        => 'boolean',
        'offers_first_free_session'  => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'stripe_charges_enabled'     => 'boolean',
        'stripe_payouts_enabled'     => 'boolean',
        'verified_at'                => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(TherapistCredential::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TherapistSchedule::class)->where('is_active', true)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function unavailabilities(): HasMany
    {
        return $this->hasMany(TherapistUnavailability::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(TherapistReview::class)->where('is_published', true);
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->status === 'approved';
    }

    public function isStripeReady(): bool
    {
        return $this->stripe_onboarding_complete
            && $this->stripe_charges_enabled
            && $this->stripe_payouts_enabled;
    }

    public function recalculateRating(): void
    {
        $avg   = $this->reviews()->avg('rating');
        $count = $this->reviews()->count();

        $this->update([
            'rating'        => $avg ? round($avg, 2) : null,
            'total_reviews' => $count,
        ]);
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeWithSpecialty($query, string $specialty)
    {
        return $query->whereJsonContains('specialties', $specialty);
    }

    public function scopeWithLanguage($query, string $lang)
    {
        return $query->whereJsonContains('languages', $lang);
    }
}
