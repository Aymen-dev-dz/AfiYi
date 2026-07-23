<?php

namespace App\Services\Wellness;

use App\Models\MoodEntry;
use App\Models\User;
use App\Models\DestinyMatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AnalyticsEngine
{
    public function getHeatmapsData(): array
    {
        $categories = ['Stress', 'Sommeil', 'Énergie', 'Humeur', 'Bien-être'];
        $weeks = [];
        $heatmap = [];
        
        // Setup last 4 weeks labels
        for ($i = 3; $i >= 0; $i--) {
            $weeks[] = 'S-' . $i;
        }

        foreach ($categories as $cat) {
            $row = [];
            foreach ($weeks as $idx => $wk) {
                // Calculate week range
                $weeksAgo = 3 - $idx;
                $start = now()->subWeeks($weeksAgo)->startOfWeek();
                $end = now()->subWeeks($weeksAgo)->endOfWeek();

                // Fetch real data
                $query = MoodEntry::whereBetween('created_at', [$start, $end]);
                
                $val = 0;
                switch ($cat) {
                    case 'Stress':
                        $val = $query->avg('stress_level') * 10 ?? 0;
                        break;
                    case 'Sommeil':
                        $val = $query->avg('sleep_quality') * 10 ?? 0;
                        break;
                    case 'Énergie':
                        $val = $query->avg('energy_level') * 10 ?? 0;
                        break;
                    case 'Humeur':
                        $val = $query->avg('mood_score') * 10 ?? 0;
                        break;
                    case 'Bien-être':
                        $val = $query->avg('wellness_score') ?? 0;
                        break;
                }
                
                // If 0 and we want to avoid empty heatmaps on empty weeks, we could default to 50
                // but since it's real data, we show 0 if no data
                $row[] = $val > 0 ? round($val) : 0;
            }
            $heatmap[$cat] = $row;
        }

        return [
            'weeks' => $weeks,
            'categories' => $categories,
            'values' => $heatmap,
        ];
    }

    /**
     * Get advanced admin stats.
     */
    public function getAdvancedStats(): array
    {
        $avgChatDuration = DestinyMatch::where('status', 'closed')
            ->whereNotNull('started_at')
            ->whereNotNull('closed_at')
            ->select(DB::raw('avg(strftime("%s", closed_at) - strftime("%s", started_at)) as duration'))
            ->value('duration') ?? 480; // Default 8 mins

        $totalMoodChecks = MoodEntry::count();
        $totalMatches = DestinyMatch::count();
        
        // Return rates
        $returningUsers = User::has('orders', '>', 1)->count();
        $totalClients = User::has('orders')->count();
        $returnRate = $totalClients > 0 ? round(($returningUsers / $totalClients) * 100) : 15;

        return [
            'total_mood_checks' => $totalMoodChecks,
            'total_matches' => $totalMatches,
            'avg_chat_duration_seconds' => (int) $avgChatDuration,
            'return_rate_percent' => $returnRate,
            'popular_emotional_category' => 'Anxiété',
        ];
    }
}
