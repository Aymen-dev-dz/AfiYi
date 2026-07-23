<?php

namespace App\Livewire;

use App\Models\PreConsultationMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\Attributes\On;

class ChatNotificationBadge extends Component
{
    public $consultationId;

    #[On('messageRead')]
    #[On('messageSent')]
    public function refreshBadge()
    {
        // Just triggers a re-render
    }

    public function render()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('pre_consultation_messages')) {
            return view('livewire.chat-notification-badge', ['unreadCount' => 0]);
        }

        $unreadCount = PreConsultationMessage::where('consultation_id', $this->consultationId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->count();

        return view('livewire.chat-notification-badge', compact('unreadCount'));
    }
}
