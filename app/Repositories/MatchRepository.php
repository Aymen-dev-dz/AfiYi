<?php

namespace App\Repositories;

use App\Models\DestinyMatch;
use App\Models\DestinyConnection;
use App\Models\AnonymousMessage;
use App\Models\AnonymousChatRating;
use Illuminate\Support\Collection;

class MatchRepository
{
    public function findWaitingMatch(string $mode, ?string $topic = null, ?string $excludeUserId = null): ?DestinyMatch
    {
        $query = DestinyMatch::where('status', 'waiting')
            ->where('match_mode', $mode);

        if ($excludeUserId) {
            $query->where('user_a_id', '!=', $excludeUserId);
        }

        if ($topic) {
            $query->where('topic', $topic);
        }

        return $query->orderBy('created_at')->first();
    }

    public function createMatch(array $data): DestinyMatch
    {
        return DestinyMatch::create($data);
    }

    public function getActiveConnectionsForUser(int $userId): Collection
    {
        return DestinyConnection::where('user_id', $userId)
            ->where('status', 'active')
            ->get();
    }

    public function getPastMatchesForUser(int $userId, int $limit = 5): Collection
    {
        return DestinyMatch::where(function ($q) use ($userId) {
                $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
            })
            ->where('status', 'closed')
            ->latest('closed_at')
            ->take($limit)
            ->get();
    }

    public function saveChatRating(array $data): AnonymousChatRating
    {
        return AnonymousChatRating::create($data);
    }

    public function getReputationForUser(int $userId): array
    {
        $ratings = AnonymousChatRating::where('rated_user_id', $userId)->get();
        $totalChats = DestinyMatch::where(function ($q) use ($userId) {
                $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
            })
            ->where('status', 'closed')
            ->count();

        if ($ratings->isEmpty()) {
            return [
                'label' => 'Helpful Listener',
                'stars' => 5,
                'conversations' => $totalChats,
                'positive_percent' => 100,
            ];
        }

        $positives = $ratings->where('rating', 1)->count();
        $positivePercent = round(($positives / $ratings->count()) * 100);

        // Map positive percent to stars
        $stars = 1;
        if ($positivePercent >= 90) $stars = 5;
        elseif ($positivePercent >= 75) $stars = 4;
        elseif ($positivePercent >= 50) $stars = 3;
        elseif ($positivePercent >= 25) $stars = 2;

        return [
            'label' => 'Helpful Listener',
            'stars' => $stars,
            'conversations' => $totalChats,
            'positive_percent' => $positivePercent,
        ];
    }
}
