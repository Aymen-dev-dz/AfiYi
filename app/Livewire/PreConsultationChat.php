<?php

namespace App\Livewire;

use App\Models\Consultation;
use App\Models\PreConsultationMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PreConsultationChat extends Component
{
    public $consultationId;
    public $newMessage = '';
    public $messages = [];
    public $isOpen = false;

    protected $listeners = ['openPreConsultationChat' => 'openChat'];

    public function openChat($id)
    {
        $this->consultationId = $id;
        $this->isOpen = true;
        $this->loadMessages();
    }

    public function closeChat()
    {
        $this->isOpen = false;
        $this->consultationId = null;
    }

    public function loadMessages()
    {
        if ($this->consultationId) {
            $unreadCount = PreConsultationMessage::where('consultation_id', $this->consultationId)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            if ($unreadCount > 0) {
                $this->dispatch('messageRead');
            }

            $this->messages = PreConsultationMessage::where('consultation_id', $this->consultationId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        }
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage)) || !$this->consultationId) return;

        PreConsultationMessage::create([
            'consultation_id' => $this->consultationId,
            'sender_id' => Auth::id(),
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
    }

    public function render()
    {
        return view('livewire.pre-consultation-chat');
    }
}
