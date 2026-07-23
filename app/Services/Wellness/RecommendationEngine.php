<?php

namespace App\Services\Wellness;

use App\Repositories\ProductRepository;
use App\Repositories\TherapistRepository;
use App\Repositories\ActivityRepository;

class RecommendationEngine
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly TherapistRepository $therapistRepository,
        private readonly ActivityRepository $activityRepository,
    ) {}

    /**
     * Build the unified recommendations object.
     */
    public function recommend(int $score, string $riskLevel, array $indicators): array
    {
        // Define primary need based on the lowest score (or highest stress)
        $primaryNeed = 'stress';
        $minVal = 10;
        
        foreach ($indicators as $key => $val) {
            if ($key === 'stress') {
                $checkVal = 10 - $val; // Higher stress = lower checkVal
            } else {
                $checkVal = $val;
            }

            if ($checkVal < $minVal) {
                $minVal = $checkVal;
                $primaryNeed = $key;
            }
        }

        // Map need to tags for product searching
        $tags = match ($primaryNeed) {
            'stress' => ['relaxation', 'stress', 'candle'],
            'sleep' => ['sleep', 'essential oil', 'tea'],
            'energy' => ['energy', 'journal', 'accessory'],
            'social' => ['community', 'peer', 'talk'],
            default => ['wellness'],
        };

        // Fetch recommendations from Repositories
        $recommendedProducts = $this->productRepository->getRecommendedForMood($tags, 3);
        
        // Fetch matching activities
        $allActivities = $this->activityRepository->getAll();
        $recommendedActivities = $allActivities->filter(fn($act) => 
            $act['category'] === $primaryNeed || ($primaryNeed === 'social' && $act['category'] === 'breathing')
        )->take(2)->values();

        if ($recommendedActivities->isEmpty()) {
            $recommendedActivities = $allActivities->take(2)->values();
        }

        // Fetch psychologists
        $recommendedPsychologists = collect();
        if ($riskLevel === 'critical' || $riskLevel === 'high') {
            $specialty = match ($primaryNeed) {
                'stress' => 'Stress Management',
                'energy' => 'Burnout',
                default => 'Anxiety',
            };
            $recommendedPsychologists = collect($this->therapistRepository->getApproved(null, $specialty, 2)->items());
        }

        // Determine next best action based on risk escalation
        $nextBestAction = match ($riskLevel) {
            'critical' => 'psychologist',
            'high' => 'chat',
            'moderate' => 'breathing',
            default => 'journal',
        };

        return [
            'wellness_score' => $score,
            'risk' => $riskLevel,
            'primary_need' => $primaryNeed,
            'recommended_products' => $recommendedProducts,
            'recommended_activities' => $recommendedActivities,
            'recommended_psychologists' => $recommendedPsychologists,
            'next_best_action' => $nextBestAction,
        ];
    }
}
