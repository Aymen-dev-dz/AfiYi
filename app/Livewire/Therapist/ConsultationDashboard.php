<?php

namespace App\Livewire\Therapist;

use App\Models\Consultation;
use App\Models\TherapistProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultationDashboard extends Component
{
    use WithPagination;

    public string $tab = 'upcoming'; // upcoming | past | messages
    public ?int $selectedNoteConsultationId = null;
    public string $noteContent = '';
    public string $noteVisibility = 'therapist_only';

    // Patient Profile Modal
    public ?int $viewingPatientId = null;
    public $patientData = null;

    // Messages section
    public ?int $selectedConsultationId = null;
    public string $newMessageText = '';

    public function selectConsultation(int $id): void
    {
        $this->selectedConsultationId = $id;
    }

    public function sendMessageText(): void
    {
        $this->validate([
            'newMessageText' => 'required|string|max:1000',
        ]);

        \App\Models\PreConsultationMessage::create([
            'consultation_id' => $this->selectedConsultationId,
            'sender_id' => Auth::id(),
            'message' => $this->newMessageText,
        ]);

        $this->newMessageText = '';
        $this->dispatch('messageSent');
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function viewPatientProfile(int $patientId): void
    {
        $patient = \App\Models\User::findOrFail($patientId);
        
        $moods = $patient->moodEntries()->latest()->take(30)->get();
        $recentOrders = $patient->orders()->with('items.product')->latest()->take(5)->get();

        // Calculate some basic stats
        $this->patientData = [
            'name' => $patient->name,
            'email' => $patient->email,
            'joined_at' => $patient->created_at->format('M Y'),
            'shares_mood' => true,
            'recent_moods' => $moods,
            'avg_mood' => $moods->avg('mood_score') ? round($moods->avg('mood_score'), 1) : null,
            'avg_stress' => $moods->avg('stress_level') ? round($moods->avg('stress_level'), 1) : null,
            'avg_sleep' => $moods->avg('sleep_quality') ? round($moods->avg('sleep_quality'), 1) : null,
            'avg_energy' => $moods->avg('energy_level') ? round($moods->avg('energy_level'), 1) : null,
            'recent_orders' => $recentOrders,
        ];
        
        $this->viewingPatientId = $patientId;
    }

    public function closePatientProfile(): void
    {
        $this->viewingPatientId = null;
        $this->patientData = null;
    }

    public function openNote(int $consultationId): void
    {
        $this->selectedNoteConsultationId = $consultationId;
        $this->noteContent = '';
        $this->noteVisibility = 'therapist_only';
    }

    public function saveNote(): void
    {
        $this->validate([
            'noteContent'    => 'required|min:10',
            'noteVisibility' => 'required|in:therapist_only,shared_with_patient',
        ]);

        $consultation = Consultation::with('notes')->findOrFail($this->selectedNoteConsultationId);

        $profile = $this->getTherapistProfile();
        if (!$profile || $consultation->therapist_profile_id !== $profile->id) {
            $this->addError('noteContent', 'Action non autorisée.');
            return;
        }

        $consultation->notes()->create([
            'therapist_profile_id' => $profile->id,
            'content_encrypted'    => $this->noteContent,   // cast 'encrypted' chiffre automatiquement
            'visibility'           => $this->noteVisibility,
        ]);

        $this->selectedNoteConsultationId = null;
        $this->noteContent = '';
        $this->dispatch('note-saved');
        session()->flash('success', 'Note saved successfully.');
    }

    public function render()
    {
        $profile = $this->getTherapistProfile();

        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $upcoming = $emptyPaginator;
        $past = $emptyPaginator;
        $monthRevenue = $totalRevenue = $totalSessions = $todayCount = 0;

        if ($profile) {
            $query = Consultation::where('therapist_profile_id', $profile->id)
                ->with(['patient', 'notes']);

            $upcoming = (clone $query)
                ->whereIn('status', [Consultation::STATUS_CONFIRMED, Consultation::STATUS_PAID, Consultation::STATUS_IN_PROGRESS])
                ->where('scheduled_at', '>', now())
                ->orderBy('scheduled_at')
                ->paginate(10, pageName: 'upcoming_page');

            $past = (clone $query)
                ->where('status', Consultation::STATUS_COMPLETED)
                ->orderByDesc('scheduled_at')
                ->paginate(10, pageName: 'past_page');

            $monthRevenue = (clone $query)
                ->where('status', Consultation::STATUS_COMPLETED)
                ->whereMonth('scheduled_at', now()->month)
                ->whereYear('scheduled_at', now()->year)
                ->sum('price');

            $totalRevenue = (clone $query)
                ->where('status', Consultation::STATUS_COMPLETED)
                ->sum('price');

            $todayCount = (clone $query)
                ->whereDate('scheduled_at', today())
                ->count();

            $totalSessions = $profile->total_sessions;

            $dismissed = session('dismissed_notifications', []);

            $activeCalls = (clone $query)
                ->whereIn('status', [Consultation::STATUS_CONFIRMED, Consultation::STATUS_PAID, Consultation::STATUS_IN_PROGRESS])
                ->whereDate('scheduled_at', today())
                ->whereNotNull('started_at')
                ->whereNotIn('id', $dismissed)
                ->get();

            $newReservations = (clone $query)
                ->where('created_at', '>=', now()->subHours(24))
                ->whereNotIn('id', $dismissed)
                ->get();

            // Load conversations for the messages tab
            $conversations = Consultation::where('therapist_profile_id', $profile->id)
                ->with('patient')
                ->latest('scheduled_at')
                ->get();

            if ($this->tab === 'messages') {
                if (!$this->selectedConsultationId && $conversations->isNotEmpty()) {
                    $this->selectedConsultationId = $conversations->first()->id;
                }
            }

            $chatMessages = collect();
            if ($this->selectedConsultationId) {
                \App\Models\PreConsultationMessage::where('consultation_id', $this->selectedConsultationId)
                    ->where('sender_id', '!=', Auth::id())
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                $chatMessages = \App\Models\PreConsultationMessage::where('consultation_id', $this->selectedConsultationId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('livewire.therapist.consultation-dashboard', [
            'upcoming'        => $upcoming,
            'past'            => $past,
            'monthRevenue'    => $monthRevenue,
            'totalRevenue'    => $totalRevenue,
            'totalSessions'   => $totalSessions,
            'todayCount'      => $todayCount,
            'activeCalls'     => $activeCalls ?? collect(),
            'newReservations' => $newReservations ?? collect(),
            'conversations'   => $conversations ?? collect(),
            'chatMessages'    => $chatMessages ?? collect(),
        ])->layout('components.layouts.app');
    }

    public function dismissNotification(int $consultationId): void
    {
        $dismissed = session('dismissed_notifications', []);
        $dismissed[] = $consultationId;
        session(['dismissed_notifications' => $dismissed]);
    }

    private function getTherapistProfile(): ?TherapistProfile
    {
        return TherapistProfile::where('user_id', Auth::id())->first();
    }
}
