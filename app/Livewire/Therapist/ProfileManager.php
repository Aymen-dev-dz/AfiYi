<?php

namespace App\Livewire\Therapist;

use App\Models\TherapistProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileManager extends Component
{
    use WithFileUploads;

    // ── Profile state ───────────────────────────────────────────────
    public ?int $profileId = null;

    public string $title = '';
    public string $bio = '';
    public string $licenseNumber = '';
    public string $licenseIssuer = '';
    public int $experienceYears = 0;
    public string $sessionPrice = '0';
    public int $sessionDurationMinutes = 50;
    public string $currency = 'DZD';
    public bool $acceptsNewClients = true;
    public bool $offersFirstFreeSession = false;

    // JSON arrays stored as comma-separated strings in the UI
    public string $specialtiesInput = '';
    public string $languagesInput = '';
    public string $approachesInput = '';

    // Photo
    public $photo = null;       // Livewire temp upload
    public ?string $photoPath = null;  // existing stored path

    // ── Status ──────────────────────────────────────────────────────
    public bool $saved = false;

    // ── Validation rules ────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'title'                  => 'nullable|string|max:100',
            'bio'                    => 'required|string|min:50|max:3000',
            'licenseNumber'          => 'nullable|string|max:100',
            'licenseIssuer'          => 'nullable|string|max:100',
            'experienceYears'        => 'required|integer|min:0|max:60',
            'sessionPrice'           => 'required|numeric|min:0',
            'sessionDurationMinutes' => 'required|integer|min:15|max:240',
            'currency'               => 'required|string|size:3',
            'acceptsNewClients'      => 'boolean',
            'offersFirstFreeSession' => 'boolean',
            'specialtiesInput'       => 'nullable|string|max:1000',
            'languagesInput'         => 'nullable|string|max:500',
            'approachesInput'        => 'nullable|string|max:500',
            'photo'                  => 'nullable|image|max:2048',
        ];
    }

    // ── Mount ────────────────────────────────────────────────────────
    public function mount(): void
    {
        $profile = TherapistProfile::where('user_id', Auth::id())->first();

        if ($profile) {
            $this->profileId              = $profile->id;
            $this->title                  = $profile->title ?? '';
            $this->bio                    = $profile->bio ?? '';
            $this->licenseNumber          = $profile->license_number ?? '';
            $this->licenseIssuer          = $profile->license_issuer ?? '';
            $this->experienceYears        = $profile->experience_years ?? 0;
            $this->sessionPrice           = (string) ($profile->session_price ?? 0);
            $this->sessionDurationMinutes = $profile->session_duration_minutes ?? 50;
            $this->currency               = $profile->currency ?? 'DZD';
            $this->acceptsNewClients      = $profile->accepts_new_clients ?? true;
            $this->offersFirstFreeSession = $profile->offers_first_free_session ?? false;
            $this->photoPath              = $profile->photo ?? null;

            $this->specialtiesInput = implode(', ', $profile->specialties ?? []);
            $this->languagesInput   = implode(', ', $profile->languages ?? []);
            $this->approachesInput  = implode(', ', $profile->approaches ?? []);
        }
    }

    // ── Save ─────────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $photoPath = $this->photoPath;

        if ($this->photo) {
            // Delete old photo
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('therapist-photos', 'public');
        }

        $data = [
            'user_id'                   => Auth::id(),
            'title'                     => $this->title ?: null,
            'bio'                       => $this->bio,
            'license_number'            => $this->licenseNumber ?: null,
            'license_issuer'            => $this->licenseIssuer ?: null,
            'experience_years'          => $this->experienceYears,
            'session_price'             => $this->sessionPrice,
            'session_duration_minutes'  => $this->sessionDurationMinutes,
            'currency'                  => strtoupper($this->currency),
            'accepts_new_clients'       => $this->acceptsNewClients,
            'offers_first_free_session' => $this->offersFirstFreeSession,
            'specialties'               => $this->parseTagInput($this->specialtiesInput),
            'languages'                 => $this->parseTagInput($this->languagesInput),
            'approaches'                => $this->parseTagInput($this->approachesInput),
            'photo'                     => $photoPath,
        ];

        $profile = TherapistProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        $this->profileId = $profile->id;
        $this->photoPath = $photoPath;
        $this->photo     = null;
        $this->saved     = true;

        $this->dispatch('profile-saved');
    }

    // ── Helpers ──────────────────────────────────────────────────────
    private function parseTagInput(string $input): array
    {
        if (trim($input) === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $input))
        ));
    }

    // ── Render ───────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.therapist.profile-manager')
            ->layout('components.layouts.app');
    }
}
