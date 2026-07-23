<?php

namespace App\Livewire\Teletherapy;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use Livewire\Component;
use Livewire\Attributes\On;

class TeletherapyChat extends Component
{
    public Consultation $consultation;
    public string $newMessage = '';
    
    // Using an array of message arrays is often easier to append to in Livewire
    // than dealing with full Eloquent collections across renders.
    public array $messages = [];

    public function mount(Consultation $consultation)
    {
        $this->consultation = $consultation;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->consultation->messages()
            ->with('sender')
            ->oldest()
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name,
                    'message' => $msg->message,
                    'is_system' => $msg->is_system,
                    'created_at' => $msg->created_at->format('H:i'),
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        if (auth()->user()->cannot('sendMessages', $this->consultation)) {
            abort(403);
        }

        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        $message = $this->consultation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';

        // Manually push to our local array so the sender sees it immediately
        $this->messages[] = [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_name' => auth()->user()->name,
            'message' => $message->message,
            'is_system' => $message->is_system,
            'created_at' => $message->created_at->format('H:i'),
        ];

        // Broadcast to others safely
        try {
            broadcast(new \App\Events\TeletherapyMessageSent($message))->toOthers();
        } catch (\Exception $e) {
            try {
                broadcast(new \App\Events\TeletherapyMessageSent($message));
            } catch (\Exception $e2) {
                // Ignore broadcaster connection failure
            }
        }
    }

    #[On('echo-private:consultation.{consultation.id},\\App\\Events\\TeletherapyMessageSent')]

    public function onMessageReceived($event)
    {
        // $event contains the broadcasted data
        $this->messages[] = [
            'id' => $event['message']['id'] ?? uniqid(),
            'sender_id' => $event['message']['sender_id'],
            'sender_name' => $event['sender_name'],
            'message' => $event['message']['message'],
            'is_system' => $event['message']['is_system'],
            'created_at' => \Carbon\Carbon::parse($event['message']['created_at'])->format('H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.teletherapy.teletherapy-chat');
    }
}
