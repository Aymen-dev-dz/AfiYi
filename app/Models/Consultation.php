<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'patient_id', 'therapist_profile_id', 'status', 'type',
        'scheduled_at', 'duration_minutes', 'price', 'currency',
        'is_first_session', 'is_free',
        'daily_room_name', 'daily_room_url', 'patient_token', 'therapist_token',
        'room_created_at', 'room_expires_at',
        'payment_intent_id', 'paid_at',
        'started_at', 'ended_at', 'actual_duration_minutes',
        'cancellation_reason', 'cancelled_at',
    ];

    protected $casts = [
        'scheduled_at'    => 'datetime',
        'room_created_at' => 'datetime',
        'room_expires_at' => 'datetime',
        'paid_at'         => 'datetime',
        'started_at'      => 'datetime',
        'ended_at'        => 'datetime',
        'cancelled_at'    => 'datetime',
        'price'           => 'decimal:2',
        'is_first_session'=> 'boolean',
        'is_free'         => 'boolean',
    ];

    const STATUS_PENDING            = 'pending';
    const STATUS_CONFIRMED          = 'confirmed';
    const STATUS_PAYMENT_PENDING    = 'payment_pending';
    const STATUS_PAID               = 'paid';
    const STATUS_IN_PROGRESS        = 'in_progress';
    const STATUS_COMPLETED          = 'completed';
    const STATUS_CANCELLED_PATIENT  = 'cancelled_patient';
    const STATUS_CANCELLED_THERAPIST= 'cancelled_therapist';
    const STATUS_NO_SHOW            = 'no_show';
    const STATUS_RESCHEDULED        = 'rescheduled';

    // ── Relationships ──────────────────────────────────────────────
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapistProfile(): BelongsTo
    {
        return $this->belongsTo(TherapistProfile::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ConsultationStatusHistory::class)->latest();
    }

    /**
     * Get the consultation notes written by the therapist.
     */
    public function notes()
    {
        return $this->hasMany(ConsultationNote::class);
    }

    /**
     * Get the chat messages for this consultation.
     */
    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(TherapistReview::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(ConsultationReminder::class);
    }

    // ── Helpers ────────────────────────────────────────────────────
    public static function generateReference(): string
    {
        do {
            $ref = 'CONS-' . strtoupper(substr(md5(uniqid('', true)), 0, 8));
        } while (static::where('reference', $ref)->exists());
        return $ref;
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null || $this->is_free;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING, self::STATUS_CONFIRMED,
            self::STATUS_PAYMENT_PENDING, self::STATUS_PAID,
        ]);
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at > now()
            && in_array($this->status, [
                self::STATUS_CONFIRMED, self::STATUS_PAID
            ]);
    }

    public function getRoomReadyAttribute(): bool
    {
        return $this->daily_room_url !== null
            && $this->room_expires_at !== null
            && $this->room_expires_at > now();
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_PAID]);
    }

    public function scopeForTherapist($query, int $therapistProfileId)
    {
        return $query->where('therapist_profile_id', $therapistProfileId);
    }
}
