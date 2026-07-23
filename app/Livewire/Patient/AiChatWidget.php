<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\AiConversation;
use App\Services\AI\WellnessAssistantService;
use Illuminate\Support\Facades\Auth;

class AiChatWidget extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $activeMode = 'chat'; // chat, breathing

    protected $listeners = ['toggle-ai-chat' => 'toggleChat'];

    public function mount()
    {
        $conversation = AiConversation::firstOrCreate(
            ['user_id' => Auth::id()],
            ['title' => 'Wellness Chat']
        );
        
        if ($conversation->messages()->count() === 0) {
            $conversation->messages()->create([
                'sender' => 'assistant',
                'message' => "Bonjour ! Je suis votre coach bien-être. Comment vous sentez-vous aujourd'hui ? Si vous le souhaitez, vous pouvez aussi démarrer un exercice de respiration guidée."
            ]);
        }
        
        $this->messages = $conversation->messages()->oldest()->get()->map(function ($msg) {
            return [
                'role' => $msg->sender,
                'content' => $msg->message
            ];
        })->toArray();
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
        if (!$this->isOpen) {
            $this->activeMode = 'chat';
        }
    }

    public function clearChat()
    {
        $conversation = AiConversation::where('user_id', Auth::id())->first();
        if ($conversation) {
            $conversation->messages()->delete();
        }
        $this->messages = [];
        $this->activeMode = 'chat';
        $this->mount();
    }

    public function triggerSuggestion(string $prompt, WellnessAssistantService $aiService)
    {
        $this->message = $prompt;
        $this->sendMessage($aiService);
    }

    public function startBreathing()
    {
        $this->activeMode = 'breathing';
    }

    public function stopBreathing()
    {
        $this->activeMode = 'chat';
    }

    public function sendMessage(WellnessAssistantService $aiService)
    {
        $text = trim($this->message);
        if (empty($text)) {
            return;
        }

        $conversation = AiConversation::firstOrCreate(['user_id' => Auth::id()]);

        // Add user message
        $conversation->messages()->create([
            'sender' => 'user',
            'message' => $text
        ]);
        
        $this->messages[] = ['role' => 'user', 'content' => $text];
        $this->message = '';

        // Get AI response
        $response = $aiService->analyzeAndRespond($text, $this->messages);
        
        // Add AI message
        $conversation->messages()->create([
            'sender' => 'assistant',
            'message' => $response
        ]);

        $this->messages[] = ['role' => 'assistant', 'content' => $response];
    }

    public function getSentimentProperty(): string
    {
        $lastUserMsg = collect($this->messages)
            ->where('role', 'user')
            ->last();

        if (!$lastUserMsg) {
            return '🧘 Calme';
        }

        $txt = mb_strtolower($lastUserMsg['content']);
        if (str_contains($txt, 'triste') || str_contains($txt, 'pleur') || str_contains($txt, 'mal') || str_contains($txt, 'tristesse')) {
            return '😢 Triste';
        }
        if (str_contains($txt, 'stress') || str_contains($txt, 'anx') || str_contains($txt, 'peur') || str_contains($txt, 'inquiet')) {
            return '😰 Stressé';
        }
        if (str_contains($txt, 'fatigu') || str_contains($txt, 'sommeil') || str_contains($txt, 'épuis')) {
            return '😴 Épuisé';
        }
        if (str_contains($txt, 'bien') || str_contains($txt, 'heureux') || str_contains($txt, 'joie') || str_contains($txt, 'super')) {
            return '😊 Joyeux';
        }

        return '🍃 Serein';
    }

    public function render()
    {
        return view('livewire.patient.ai-chat-widget');
    }
}
