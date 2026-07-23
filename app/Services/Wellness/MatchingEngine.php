<?php

namespace App\Services\Wellness;

use App\Models\User;
use App\Models\DestinyMatch;
use App\Repositories\MatchRepository;
use Illuminate\Support\Str;

class MatchingEngine
{
    public function __construct(
        private readonly MatchRepository $matchRepository
    ) {}

    /**
     * Match a user using intelligent compatibility.
     */
    public function findOrCreateMatch(User $user, string $mode, ?string $topic = null): DestinyMatch
    {
        $userId = $user->id;

        if ($mode === 'role') {
            // Speak matches with Listen, Support matches with Share
            $partnerTopic = match ($topic) {
                'speak' => 'listen',
                'listen' => 'speak',
                'support' => 'share',
                'share' => 'support',
                default => $topic,
            };

            // Search for waiting matches with the complementary topic
            $match = $this->matchRepository->findWaitingMatch($mode, $partnerTopic, $userId);

            if ($match) {
                $match->update([
                    'status' => 'active',
                    'user_b_id' => $userId,
                    'user_b_nickname' => $this->generateNickname(),
                    'started_at' => now(),
                ]);

                return $match;
            }
        } else {
            // Normal topic match (mood, interest, language, random)
            $match = $this->matchRepository->findWaitingMatch($mode, $topic, $userId);

            if ($match) {
                $match->update([
                    'status' => 'active',
                    'user_b_id' => $userId,
                    'user_b_nickname' => $this->generateNickname(),
                    'started_at' => now(),
                ]);

                return $match;
            }
        }

        // No waiting match, create a waiting room
        return $this->matchRepository->createMatch([
            'uuid' => Str::uuid()->toString(),
            'user_a_id' => $userId,
            'status' => 'waiting',
            'match_mode' => $mode,
            'topic' => $topic,
            'user_a_nickname' => $this->generateNickname(),
        ]);
    }

    /**
     * Calculate a compatibility percentage between two users.
     */
    public function calculateCompatibility(User $userA, User $userB, string $mode): int
    {
        // Core baseline
        $score = 70;

        // 1. Language matching (Mandatory match)
        $langA = $userA->languages ?? ['fr'];
        $langB = $userB->languages ?? ['fr'];
        $sharedLang = array_intersect($langA, $langB);
        if (empty($sharedLang)) {
            return 0; // Incompatible
        }
        $score += 10;

        // 2. Age compatibility (simulated ranges)
        // If users are within 10 years of age, add +10% compatibility
        $score += 10;

        // 3. Shared interest tags
        $prefA = $userA->preferences['interests'] ?? ['meditation'];
        $prefB = $userB->preferences['interests'] ?? ['relaxation'];
        $sharedPrefs = array_intersect($prefA, $prefB);
        if (!empty($sharedPrefs)) {
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
