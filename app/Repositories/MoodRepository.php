<?php

namespace App\Repositories;

use App\Models\MoodEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MoodRepository
{
    public function create(array $data): MoodEntry
    {
        return MoodEntry::create($data);
    }

    public function getLatestForUser(int $userId): ?MoodEntry
    {
        return MoodEntry::where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function getHistoryForUser(int $userId, int $limit = 7): Collection
    {
        return MoodEntry::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getRangeForUser(int $userId, Carbon $startDate, Carbon $endDate): Collection
    {
        return MoodEntry::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getStreakForUser(int $userId): int
    {
        $entries = MoodEntry::where('user_id', $userId)
            ->select('created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($e) => $e->created_at->toDateString())
            ->unique()
            ->values();

        if ($entries->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $currentDate = Carbon::today();

        // Check if user logged today or yesterday
        $firstEntryDate = Carbon::parse($entries[0]);
        if (!$firstEntryDate->isToday() && !$firstEntryDate->isYesterday()) {
            return 0;
        }

        $targetDate = $firstEntryDate;
        foreach ($entries as $dateStr) {
            $entryDate = Carbon::parse($dateStr);
            if ($targetDate->diffInDays($entryDate) <= 1) {
                $streak++;
                $targetDate = $entryDate;
            } else {
                break;
            }
        }

        return $streak;
    }
}
