<?php

namespace App\Livewire\Therapist;

use App\Models\TherapistProfile;
use App\Models\TherapistSchedule;
use App\Models\TherapistUnavailability;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScheduleManager extends Component
{
    // ── Weekly availability ─────────────────────────────────────────
    public bool $showAddSlotModal = false;
    public int  $slotDay = 1;           // 0=Sun … 6=Sat
    public string $slotStart = '09:00';
    public string $slotEnd   = '17:00';

    // ── Unavailability ──────────────────────────────────────────────
    public string $unavailStart  = '';
    public string $unavailEnd    = '';
    public string $unavailReason = '';

    // ── Flash ───────────────────────────────────────────────────────
    public ?string $successMessage = null;
    public ?string $errorMessage   = null;

    // ── Validation ──────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'slotDay'        => 'required|integer|min:0|max:6',
            'slotStart'      => 'required|date_format:H:i',
            'slotEnd'        => 'required|date_format:H:i|after:slotStart',
            'unavailStart'   => 'required|date',
            'unavailEnd'     => 'nullable|date|after_or_equal:unavailStart',
            'unavailReason'  => 'nullable|string|max:255',
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────
    private function getProfile(): ?TherapistProfile
    {
        return TherapistProfile::where('user_id', Auth::id())->first();
    }

    private function flash(string $type, string $msg): void
    {
        if ($type === 'success') {
            $this->successMessage = $msg;
            $this->errorMessage   = null;
        } else {
            $this->errorMessage   = $msg;
            $this->successMessage = null;
        }
    }

    // ── Weekly schedule CRUD ─────────────────────────────────────────
    public function openAddSlot(): void
    {
        $this->resetValidation();
        $this->slotDay   = 1;
        $this->slotStart = '09:00';
        $this->slotEnd   = '17:00';
        $this->showAddSlotModal = true;
    }

    public function saveSlot(): void
    {
        $this->validate([
            'slotDay'   => 'required|integer|min:0|max:6',
            'slotStart' => 'required|date_format:H:i',
            'slotEnd'   => 'required|date_format:H:i|after:slotStart',
        ]);

        $profile = $this->getProfile();
        if (!$profile) {
            $this->flash('error', 'Please complete your profile first.');
            return;
        }

        // Prevent exact duplicates
        $exists = TherapistSchedule::where('therapist_profile_id', $profile->id)
            ->where('day_of_week', $this->slotDay)
            ->where('start_time', $this->slotStart)
            ->where('end_time', $this->slotEnd)
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            $this->flash('error', 'This time slot already exists for this day.');
            return;
        }

        TherapistSchedule::create([
            'therapist_profile_id' => $profile->id,
            'day_of_week'          => $this->slotDay,
            'start_time'           => $this->slotStart,
            'end_time'             => $this->slotEnd,
            'is_active'            => true,
        ]);

        $this->showAddSlotModal = false;
        $this->flash('success', 'Availability slot added.');
        $this->dispatch('slot-saved');
    }

    public function deleteSlot(int $scheduleId): void
    {
        $profile = $this->getProfile();
        if (!$profile) return;

        TherapistSchedule::where('id', $scheduleId)
            ->where('therapist_profile_id', $profile->id)
            ->delete();

        $this->flash('success', 'Slot removed.');
    }

    // ── Unavailability CRUD ──────────────────────────────────────────
    public function addUnavailability(): void
    {
        $this->validate([
            'unavailStart'  => 'required|date',
            'unavailEnd'    => 'nullable|date|after_or_equal:unavailStart',
            'unavailReason' => 'nullable|string|max:255',
        ]);

        $profile = $this->getProfile();
        if (!$profile) {
            $this->flash('error', 'Please complete your profile first.');
            return;
        }

        TherapistUnavailability::create([
            'therapist_profile_id' => $profile->id,
            'start_date'           => $this->unavailStart,
            'end_date'             => $this->unavailEnd ?: $this->unavailStart,
            'reason'               => $this->unavailReason ?: null,
            'is_recurring'         => false,
        ]);

        $this->unavailStart  = '';
        $this->unavailEnd    = '';
        $this->unavailReason = '';
        $this->flash('success', 'Unavailability period added.');
        $this->resetValidation();
    }

    public function deleteUnavailability(int $id): void
    {
        $profile = $this->getProfile();
        if (!$profile) return;

        TherapistUnavailability::where('id', $id)
            ->where('therapist_profile_id', $profile->id)
            ->delete();

        $this->flash('success', 'Unavailability removed.');
    }

    // ── Render ───────────────────────────────────────────────────────
    public function render()
    {
        $profile       = $this->getProfile();
        $schedules     = collect();
        $unavailabilities = collect();

        if ($profile) {
            // All active slots, group by day
            $schedules = TherapistSchedule::where('therapist_profile_id', $profile->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');

            $unavailabilities = TherapistUnavailability::where('therapist_profile_id', $profile->id)
                ->orderBy('start_date')
                ->get();
        }

        return view('livewire.therapist.schedule-manager', [
            'schedules'        => $schedules,
            'unavailabilities' => $unavailabilities,
            'hasProfile'       => $profile !== null,
        ])->layout('components.layouts.app');
    }
}
