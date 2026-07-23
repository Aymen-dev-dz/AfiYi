<?php

namespace App\Livewire;

use App\Models\PreConsultationMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class GlobalChatNotification extends Component
{
    #[On('messageRead')]
    #[On('messageSent')]
    public function refreshBadge() {}

    public function render()
    {
        $user = Auth::user();
        if (!$user || $user->hasRole('Therapist') || !\Illuminate\Support\Facades\Schema::hasTable('pre_consultation_messages')) {
            return view('livewire.global-chat-notification', ['unreadCount' => 0]);
        }

        $userId = $user->id;
        $unreadCount = PreConsultationMessage::where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->whereHas('consultation', function($q) use ($userId) {
                $q->where('patient_id', $userId)
                  ->orWhereHas('therapistProfile', function($q2) use ($userId) {
                      $q2->where('user_id', $userId);
                  });
            })
            ->count();

        return view('livewire.global-chat-notification', compact('unreadCount'));
    }
}
