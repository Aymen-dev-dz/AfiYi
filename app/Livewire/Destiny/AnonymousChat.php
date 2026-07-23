<?php

namespace App\Livewire\Destiny;

use App\Events\NewAnonymousMessage;
use App\Models\AnonymousMessage;
use App\Models\DestinyMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnonymousChat extends Component
{
    public DestinyMatch $match;
    public string $messageText = '';
    public string $partnerNickname = '';
    public int $compatibilityScore = 85;
    public bool $showProfessionalSuggestion = false;
    
    // Rating properties
    public bool $showRatingModal = false;
    public int $ratingFeedback = 1; // 1 = helpful, 0 = unhelpful
    public string $ratingComment = '';
    
    // Ice breakers
    public array $iceBreakers = [
        "Qu'est-ce qui t'a fait sourire récemment ?",
        "Y a-t-il quelque chose qui te tracasse en ce moment ?",
        "Quelle est la chose pour laquelle tu as le plus de gratitude ?",
        "Si tu pouvais être n'importe où tout de suite, où serais-tu ?"
    ];

    public function mount(string $room, \App\Services\Destiny\DestinyMatchingService $matchingService)
    {
        $this->match = DestinyMatch::where('uuid', $room)->firstOrFail();

        abort_unless(
            $this->match->user_a_id === Auth::id() || $this->match->user_b_id === Auth::id(),
            403, 'Unauthorized access to this room.'
        );

        // Ensure corresponding AnonymousRoom exists (fallback for legacy/testing data)
        $existsById = \App\Models\AnonymousRoom::where('id', $this->match->id)->exists();
        $existsByUuid = \App\Models\AnonymousRoom::where('uuid', $this->match->uuid)->exists();

        if (!$existsById || !$existsByUuid) {
            \App\Models\AnonymousRoom::where('id', $this->match->id)->delete();
            \App\Models\AnonymousRoom::where('uuid', $this->match->uuid)->delete();

            $ar = new \App\Models\AnonymousRoom();
            $ar->id = $this->match->id;
            $ar->uuid = $this->match->uuid;
            $ar->user_a_id = $this->match->user_a_id;
            $ar->user_b_id = $this->match->user_b_id;
            $ar->status = $this->match->status;
            $ar->save();
        }

        $this->partnerNickname = $this->match->user_a_id === Auth::id() 
            ? ($this->match->user_b_nickname ?? 'Unknown') 
            : ($this->match->user_a_nickname ?? 'Unknown');

        $this->compatibilityScore = $matchingService->getCompatibility($this->match);

        // Check for persistent bad mood (last 3 entries average score < 5)
        $recentMoods = \App\Models\MoodEntry::where('user_id', Auth::id())->latest()->take(3)->pluck('mood_score');
        if ($recentMoods->count() >= 3 && $recentMoods->avg() < 5) {
            $this->showProfessionalSuggestion = true;
        }
    }

    public function getListeners()
    {
        return [
            "echo:anonymous.{$this->match->uuid},NewAnonymousMessage" => 'refreshMessages',
            'refresh' => '$refresh',
        ];
    }

    public function refreshMessages()
    {
        // Re-check status if closed
        $this->match->refresh();
        if ($this->match->status === 'closed' && !$this->showRatingModal) {
            $this->showRatingModal = true;
        }
    }

    public bool $showCrisisAlert = false;
    
    public function sendMessage(\App\Services\Destiny\DestinyModerationService $moderator)
    {
        $this->validate(['messageText' => 'required|string|max:1000']);

        if ($this->match->status === 'closed') return;

        $isCrisis = false;
        if (!$moderator->isMessageSafe($this->messageText, $isCrisis)) {
            if ($isCrisis) {
                $this->showCrisisAlert = true;
            } else {
                $this->addError('messageText', 'Votre message contient du contenu inapproprié ou indésirable.');
            }
            return;
        }

        $this->showCrisisAlert = false;

        $msg = AnonymousMessage::create([
            'anonymous_room_id' => $this->match->id,
            'sender_id' => Auth::id(),
            'message' => $this->messageText,
        ]);

        $this->messageText = '';

        // Broadcast event safely
        try {
            broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid))->toOthers();
        } catch (\Exception $e) {
            try {
                broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid));
            } catch (\Exception $e2) {
                // Ignore broadcaster connection failure
            }
        }
    }

    public function sendVoiceMessage()
    {
        if ($this->match->status === 'closed') return;

        $msg = AnonymousMessage::create([
            'anonymous_room_id' => $this->match->id,
            'sender_id' => Auth::id(),
            'message' => '🎤 Message vocal',
            'audio_path' => 'simulated_voice_message.mp3', // Simulated for now
        ]);

        try {
            broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid))->toOthers();
        } catch (\Exception $e) {
            // Ignore
        }
    }

    public function reactToMessage(int $messageId, string $emoji)
    {
        $message = AnonymousMessage::where('id', $messageId)
            ->where('anonymous_room_id', $this->match->id)
            ->first();

        if (!$message) return;

        $reactions = is_array($message->reactions) ? $message->reactions : json_decode($message->reactions ?? '[]', true);
        
        if (!isset($reactions[$emoji])) {
            $reactions[$emoji] = [];
        }

        $userId = Auth::id();

        if (in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji] = array_values(array_diff($reactions[$emoji], [$userId]));
            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        } else {
            $reactions[$emoji][] = $userId;
        }

        $message->reactions = empty($reactions) ? null : $reactions;
        $message->save();

        try {
            broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid))->toOthers();
        } catch (\Exception $e) {}
    }

    public function sendIceBreaker(string $text)
    {
        $this->messageText = $text;
        $this->sendMessage(new \App\Services\Destiny\DestinyModerationService());
    }

    public function endChat()
    {
        $this->match->update(['status' => 'closed', 'closed_at' => now()]);
        try {
            broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid))->toOthers();
        } catch (\Exception $e) {
            try {
                broadcast(new \App\Events\NewAnonymousMessage($this->match->uuid));
            } catch (\Exception $e2) {
                // Ignore broadcaster connection failure
            }
        }
        
        $this->showRatingModal = true;
    }

    public function submitRating()
    {
        $partnerId = $this->match->user_a_id === Auth::id() ? $this->match->user_b_id : $this->match->user_a_id;
        
        if ($partnerId) {
            \App\Models\AnonymousChatRating::create([
                'match_id' => $this->match->id,
                'rated_user_id' => $partnerId,
                'rater_user_id' => Auth::id(),
                'rating' => $this->ratingFeedback,
                'comment' => $this->ratingComment,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Merci pour votre retour bienveillant ! Session de chat fermée.');
    }

    public function blockUser()
    {
        $partnerId = $this->match->user_a_id === Auth::id() ? $this->match->user_b_id : $this->match->user_a_id;
        
        if ($partnerId) {
            \App\Models\UserBlock::create([
                'blocker_id' => Auth::id(),
                'blocked_id' => $partnerId,
                'reason' => 'Blocked from Destiny Connection',
            ]);
        }
        
        $this->endChat();
    }

    public function reportUser()
    {
        $partnerId = $this->match->user_a_id === Auth::id() ? $this->match->user_b_id : $this->match->user_a_id;
        
        if ($partnerId) {
            \App\Models\UserReport::create([
                'reporter_id' => Auth::id(),
                'reported_id' => $partnerId,
                'reason' => 'Inappropriate behavior in Destiny Connection',
                'context_type' => DestinyMatch::class,
                'context_id' => $this->match->id,
            ]);
        }
        
        $this->endChat();
    }

    public function render()
    {
        $messages = $this->match->messages()->with('sender')->oldest()->get();
        return view('livewire.destiny.anonymous-chat', compact('messages'))
            ->layout('components.layouts.app');
    }
}
