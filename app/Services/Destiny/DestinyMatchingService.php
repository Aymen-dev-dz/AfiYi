<?php

namespace App\Services\Destiny;

use App\Models\DestinyMatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DestinyMatchingService
{
    /**
     * Find a match or create a waiting room based on mode.
     */
    public function findOrCreateMatch(
        int $userId,
        string $mode,
        ?string $topic = null,
        ?string $role = null,
        int $duration = 10,
        ?int $moodScore = null
    ): DestinyMatch {
        $query = DestinyMatch::where('status', 'waiting')
            ->where('user_a_id', '!=', $userId)
            ->where('match_mode', $mode)
            ->where('duration', $duration);

        if ($role && $role !== 'both') {
            $partnerRoles = ($role === 'speak') ? ['listen', 'both'] : ['speak', 'both'];
            $query->whereIn('role', $partnerRoles);
        }

        if ($mode === 'mood' || $mode === 'interest') {
            if ($topic) {
                $query->where('topic', $topic);
            }
        }
        
        if ($moodScore !== null) {
            $query->orderByRaw('ABS(mood_score - ?) ASC', [$moodScore]);
        } else {
            $query->orderBy('created_at');
        }

        $match = $query->first();

        if ($match) {
            $match->update([
                'status' => 'active',
                'user_b_id' => $userId,
                'user_b_nickname' => $this->generateNickname(),
                'started_at' => now(),
            ]);

            \App\Models\AnonymousRoom::where('uuid', $match->uuid)->update([
                'status' => 'active',
                'user_b_id' => $userId,
            ]);
            
            return $match;
        }

        $room = \App\Models\AnonymousRoom::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user_a_id' => $userId,
            'status' => 'waiting',
        ]);

        $match = new DestinyMatch();
        // Do not force the auto-increment ID to match the room ID
        $match->uuid = $room->uuid;
        $match->user_a_id = $userId;
        $match->status = 'waiting';
        $match->match_mode = $mode;
        $match->role = $role;
        $match->duration = $duration;
        $match->mood_score = $moodScore;
        $match->topic = $topic;
        $match->user_a_nickname = $this->generateNickname();
        $match->save();

        return $match;
    }
    
    public function cancelSearch(int $matchId, int $userId): void
    {
        $match = DestinyMatch::where('id', $matchId)
            ->where('user_a_id', $userId)
            ->where('status', 'waiting')
            ->first();
            
        if ($match) {
            \App\Models\AnonymousRoom::where('uuid', $match->uuid)->delete();
            $match->delete();
        }
    }

    /**
     * Calculate compatibility percentage for an active match.
     */
    public function getCompatibility(DestinyMatch $match): int
    {
        $userA = User::find($match->user_a_id);
        $userB = User::find($match->user_b_id);

        if (!$userA || !$userB) {
            return 85; // Default fallback
        }

        $score = 75;

        // Language overlap
        $langA = $userA->languages ?? ['fr'];
        $langB = $userB->languages ?? ['fr'];
        if (array_intersect($langA, $langB)) {
            $score += 10;
        }

        // Shared interests
        $prefA = $userA->preferences['interests'] ?? [];
        $prefB = $userB->preferences['interests'] ?? [];
        if (array_intersect($prefA, $prefB)) {
            $score += 10;
        }

        return min($score, 99);
    }
    
    private function generateNickname(): string
    {
        $adjectives = ['Silent', 'Wandering', 'Gentle', 'Curious', 'Brave', 'Calm', 'Bright', 'Lunar', 'Solar', 'Mystic'];
        $nouns = ['Soul', 'Spirit', 'Traveler', 'Seeker', 'Dreamer', 'Voice', 'Echo', 'Star', 'Cloud', 'Ocean'];
        
        return $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)] . ' ' . rand(10, 99);
    }
}
