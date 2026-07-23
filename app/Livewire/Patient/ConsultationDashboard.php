<?php

namespace App\Livewire\Patient;

use App\Models\Consultation;
use App\Models\TherapistReview;
use App\Services\Teletherapy\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultationDashboard extends Component
{
    use WithPagination;

    public string $tab = 'upcoming';
    public ?int $cancellingId = null;
    public string $cancelReason = '';

    // Review modal state
    public ?int $reviewingId = null;
    public int  $rating = 5;
    public string $reviewComment = '';
    public bool $reviewAnonymous = true;
    public bool $shareMoods = false;

    // Messages tab state
    public ?int $selectedConsultationId = null;
    public string $newMessageText = '';

    public function selectConsultation(int $id)
    {
        $this->selectedConsultationId = $id;

        // Mark messages as read
        \App\Models\PreConsultationMessage::where('consultation_id', $id)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendMessageText()
    {
        $this->validate(['newMessageText' => 'required|string|max:1000']);

        if (!$this->selectedConsultationId) return;

        \App\Models\PreConsultationMessage::create([
            'consultation_id' => $this->selectedConsultationId,
            'sender_id'       => Auth::id(),
            'message'         => $this->newMessageText,
        ]);

        $this->newMessageText = '';
        
        // Dispatch to global
        $this->dispatch('messageSent');
    }

    public function mount()
    {
        $this->shareMoods = Auth::user()->share_mood_with_therapist;
    }

    public function toggleShareMoods()
    {
        $user = Auth::user();
        $user->share_mood_with_therapist = $this->shareMoods;
        $user->save();
        $this->dispatch('notify', type: 'success', message: 'Mood sharing preferences updated.');
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function openCancel(int $consultationId): void
    {
        $this->cancellingId = $consultationId;
        $this->cancelReason = '';
    }

    public function confirmCancel(BookingService $bookingService): void
    {
        $this->validate(['cancelReason' => 'required|min:5']);

        $consultation = Consultation::findOrFail($this->cancellingId);
        if ($consultation->patient_id !== Auth::id()) abort(403);

        $bookingService->cancel($consultation, 'patient', $this->cancelReason);

        $this->cancellingId = null;
        $this->dispatch('consultation-cancelled');
    }

    public function openReview(int $consultationId): void
    {
        $this->reviewingId = $consultationId;
        $this->rating = 5;
        $this->reviewComment = '';
        $this->reviewAnonymous = true;
    }

    public function submitReview(): void
    {
        $this->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'reviewComment' => 'nullable|string|max:1000',
        ]);

        $consultation = Consultation::with('therapistProfile')->findOrFail($this->reviewingId);
        if ($consultation->patient_id !== Auth::id()) abort(403);
        if ($consultation->review()->exists()) {
            $this->dispatch('review-already-exists');
            return;
        }

        TherapistReview::create([
            'consultation_id'      => $consultation->id,
            'patient_id'           => Auth::id(),
            'therapist_profile_id' => $consultation->therapist_profile_id,
            'rating'               => $this->rating,
            'comment'              => $this->reviewComment,
            'is_anonymous'         => $this->reviewAnonymous,
            'is_published'         => true,
        ]);

        // Mettre à jour la note moyenne du thérapeute
        $profile = $consultation->therapistProfile;
        $avgRating = TherapistReview::where('therapist_profile_id', $profile->id)
            ->where('is_published', true)->avg('rating');
        $profile->update([
            'rating'        => round($avgRating, 2),
            'total_reviews' => TherapistReview::where('therapist_profile_id', $profile->id)->count(),
        ]);

        $this->reviewingId = null;
        $this->dispatch('review-submitted');
    }

    public function render()
    {
        $patientId = Auth::id();
        $query = Consultation::where('patient_id', $patientId)->with(['therapistProfile.user', 'notes']);

        $upcoming = (clone $query)
            ->whereIn('status', [Consultation::STATUS_CONFIRMED, Consultation::STATUS_PAID, Consultation::STATUS_IN_PROGRESS])
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->paginate(10, pageName: 'upcoming_page');

        $past = (clone $query)
            ->where('status', Consultation::STATUS_COMPLETED)
            ->orderByDesc('scheduled_at')
            ->paginate(10, pageName: 'past_page');

        // Fetch mood data and recommendations
        $user = auth()->user();
        $recentMoods = $user->moodEntries()->latest()->take(7)->get()->reverse(); // For chart
        
        // Calculate streak
        $streak = 0;
        $currentDate = today();
        $allMoods = $user->moodEntries()->latest('created_at')->get();
        foreach ($allMoods as $mood) {
            if ($mood->created_at->isSameDay($currentDate)) {
                $streak++;
                $currentDate->subDay();
            } else if ($mood->created_at->isBefore($currentDate)) {
                break;
            }
        }

        $recommendationEngine = app(\App\Services\RecommendationEngine::class);
        $recommendations = $recommendationEngine->getRecommendations($user);

        // For Messages tab
        $conversations = Consultation::where('patient_id', $patientId)
            ->with(['therapistProfile.user'])
            ->orderByDesc('created_at')
            ->get();
            
        $chatMessages = collect();
        if ($this->tab === 'messages' && $this->selectedConsultationId) {
            $chatMessages = \App\Models\PreConsultationMessage::where('consultation_id', $this->selectedConsultationId)
                ->oldest()
                ->get()
                ->map(function ($msg) {
                    try {
                        // The model casts 'message' as encrypted, we just access it
                        $msg->message = $msg->message;
                    } catch (\Exception $e) {
                        $msg->message = 'Erreur de déchiffrement: ' . $e->getMessage();
                    }
                    return $msg;
                });
        }

        return view('livewire.patient.consultation-dashboard', compact('upcoming', 'past', 'recentMoods', 'streak', 'recommendations', 'conversations', 'chatMessages'));
    }
}
