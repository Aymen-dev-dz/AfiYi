<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Billable;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'share_mood_with_therapist',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function therapistProfile()
    {
        return $this->hasOne(TherapistProfile::class);
    }

    public function destinyConnections()
    {
        return $this->hasMany(DestinyConnection::class);
    }

    public function destinyMatches()
    {
        return DestinyMatch::where(function ($query) {
            $query->where('user_a_id', $this->id)
                  ->orWhere('user_b_id', $this->id);
        });
    }

    public function moodEntries()
    {
        return $this->hasMany(MoodEntry::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function destinyMatchesThisMonth(): int
    {
        return DestinyMatch::where(function ($query) {
            $query->where('user_a_id', $this->id)
                  ->orWhere('user_b_id', $this->id);
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    }

    public function canUseDestinyConnection(): bool
    {
        try {
            if ($this->subscribed('default')) {
                return true;
            }
        } catch (\Throwable $e) {
            // Skip cashier check if tables are missing in this environment
        }

        return $this->destinyMatchesThisMonth() < 3;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'share_mood_with_therapist' => 'boolean',
        ];
    }
}
