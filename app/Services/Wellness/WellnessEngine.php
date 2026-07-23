<?php

namespace App\Services\Wellness;

use App\Contexts\WellnessContext;
use App\Models\MoodEntry;

class WellnessEngine
{
    /**
     * Calculate score and indicators from context or raw inputs.
     */
    public function calculate(WellnessContext|array $input): array
    {
        $indicators = $this->extractIndicators($input);
        
        $weights = config('wellness.weights', [
            'stress' => 3.0,
            'sleep' => 3.0,
            'energy' => 2.0,
            'social' => 2.0,
        ]);

        $weightedSum = 0;
        $totalWeights = 0;

        foreach ($indicators as $key => $value) {
            $weight = $weights[$key] ?? 1.0;
            
            // Adjust stress so higher stress reduces the score
            if ($key === 'stress') {
                $scoreValue = 10 - $value; // 10 becomes 0, 1 becomes 9
            } else {
                $scoreValue = $value;
            }

            $weightedSum += ($scoreValue * $weight);
            $totalWeights += $weight;
        }

        $score = $totalWeights > 0 ? round(($weightedSum / ($totalWeights * 10)) * 100) : 70;

        return [
            'score' => (int) $score,
            'indicators' => $indicators,
        ];
    }

    private function extractIndicators(WellnessContext|array $input): array
    {
        if ($input instanceof WellnessContext) {
            $mood = $input->currentMood;
            if (!$mood) {
                return [
                    'stress' => 5,
                    'sleep' => 5,
                    'energy' => 5,
                    'social' => 5,
                ];
            }
            return [
                'stress' => (int) ($mood->stress_level ?? 5),
                'sleep' => (int) ($mood->sleep_quality ?? 5),
                'energy' => (int) ($mood->energy_level ?? 5),
                // Map social: if they logged lonely emotions, social score is lower
                'social' => $this->mapSocialScore($mood),
            ];
        }

        // Guest array input
        return [
            'stress' => (int) ($input['stress_level'] ?? 5),
            'sleep' => (int) ($input['sleep_quality'] ?? 5),
            'energy' => (int) ($input['energy_level'] ?? 5),
            'social' => (int) ($input['social_level'] ?? 5),
        ];
    }

    private function mapSocialScore(MoodEntry $mood): int
    {
        $emotion = strtolower($mood->emotion ?? 'neutral');
        if (in_array($emotion, ['lonely', 'anxious', 'sad', 'angry'])) {
            return 3;
        }
        if (in_array($emotion, ['happy', 'calm'])) {
            return 8;
        }
        return 5;
    }
}
