<?php

namespace App\Livewire\Destiny;

use App\Models\AnonymousRoom;
use App\Services\Destiny\DestinyMatchingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnonymousLobby extends Component
{
    public string $mode = 'random'; // random, mood, interest, language
    public string $topic = '';
    public string $role = 'speak'; // speak, listen
    public int $duration = 10; // 10, 20, 30
    public bool $isSearching = false;
    public ?int $currentMatchId = null;

    protected $listeners = ['checkMatch' => 'checkMatch'];

    public function mount()
    {
        abort_unless(session('destiny_unlocked'), 403, 'Destiny Connection not unlocked.');
    }

    public function startSearch(DestinyMatchingService $matchingService)
    {
        $this->validate([
            'mode' => 'required|in:random,mood,interest,language',
            'topic'=> 'required_if:mode,mood,interest|string|nullable',
            'role' => 'required|in:speak,listen,both',
            'duration' => 'required|in:10,20,30',
        ]);

        $this->isSearching = true;

        $latestMood = \App\Models\MoodEntry::where('user_id', Auth::id())
            ->latest()
            ->first();
        $moodScore = $latestMood ? $latestMood->wellness_score : 50;

        $match = $matchingService->findOrCreateMatch(
            userId: Auth::id(),
            mode: $this->mode,
            topic: $this->topic,
            role: $this->role,
            duration: $this->duration,
            moodScore: $moodScore
        );

        $this->currentMatchId = $match->id;

        if ($match->status === 'active') {
            $this->redirectRoute('destiny.chat', ['room' => $match->uuid]);
        }
    }

    public function cancelSearch(DestinyMatchingService $matchingService)
    {
        if ($this->currentMatchId) {
            $matchingService->cancelSearch($this->currentMatchId, Auth::id());
        }
        $this->isSearching = false;
        $this->currentMatchId = null;
    }

    public function checkMatch()
    {
        if ($this->currentMatchId) {
            $match = \App\Models\DestinyMatch::find($this->currentMatchId);
            if ($match && $match->status === 'active' && $match->user_b_id !== null) {
                $this->redirectRoute('destiny.chat', ['room' => $match->uuid]);
            }
        }
    }

    public function getAvailableUsersProperty()
    {
        return \App\Models\User::role('User')
            ->where('id', '!=', Auth::id())
            ->get();
    }

    public function startDirectChat(int $targetUserId)
    {
        // Cancel any active search
        $this->cancelSearch(new DestinyMatchingService());

        // Create a DestinyMatch with status active directly between Auth::id() and $targetUserId
        $room = \App\Models\AnonymousRoom::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user_a_id' => Auth::id(),
            'user_b_id' => $targetUserId,
            'status' => 'active',
        ]);

        $partner = \App\Models\User::find($targetUserId);

        $match = new \App\Models\DestinyMatch();
        // Do not force the auto-increment ID to match the room ID
        $match->uuid = $room->uuid;
        $match->user_a_id = Auth::id();
        $match->user_b_id = $targetUserId;
        $match->status = 'active';
        $match->match_mode = 'direct';
        $match->user_a_nickname = 'Demo ' . Auth::user()->name;
        $match->user_b_nickname = 'Demo ' . ($partner ? $partner->name : 'User');
        $match->started_at = now();
        $match->save();

        return $this->redirectRoute('destiny.chat', ['room' => $match->uuid]);
    }

    public function render()
    {
        return view('livewire.destiny.anonymous-lobby')->layout('components.layouts.app');
    }
}
