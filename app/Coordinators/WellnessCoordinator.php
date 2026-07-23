<?php

namespace App\Coordinators;

use App\Contexts\WellnessContext;
use App\Models\User;
use App\Repositories\MoodRepository;
use App\Repositories\TherapistRepository;
use App\Repositories\MatchRepository;
use App\Repositories\ActivityRepository;
use App\Services\Wellness\WellnessEngine;
use App\Services\Wellness\RiskDetectionEngine;
use App\Services\Wellness\RecommendationEngine;
use App\Services\Wellness\JourneyEngine;
use App\Services\Wellness\NotificationEngine;
use App\Services\Wellness\RewardEngine;

class WellnessCoordinator
{
    public function __construct(
        private readonly MoodRepository $moodRepository,
        private readonly TherapistRepository $therapistRepository,
        private readonly MatchRepository $matchRepository,
        private readonly ActivityRepository $activityRepository,
        private readonly WellnessEngine $wellnessEngine,
        private readonly RiskDetectionEngine $riskDetectionEngine,
        private readonly RecommendationEngine $recommendationEngine,
        private readonly JourneyEngine $journeyEngine,
        private readonly NotificationEngine $notificationEngine,
        private readonly RewardEngine $rewardEngine,
    ) {}

    /**
     * Build the entire coordinator bundle for an authenticated user.
     */
    public function buildForUser(User $user): array
    {
        $userId = $user->id;

        // 1. Fetch data from repositories
        $currentMood = $this->moodRepository->getLatestForUser($userId);
        $history = $this->moodRepository->getHistoryForUser($userId, 7);
        $completedActivities = $this->activityRepository->getCompletedToday($userId);
        
        // Orders
        $orders = \App\Models\Order::where('user_id', $userId)->get();
        $connections = $this->matchRepository->getActiveConnectionsForUser($userId);
        
        // Appointments
        $appointments = $this->therapistRepository->getUpcomingConsultationsForPatient($userId);
        
        // Streak & Reputation
        $streak = $this->moodRepository->getStreakForUser($userId);
        $reputation = $this->matchRepository->getReputationForUser($userId);

        // 2. Instantiate Context
        $context = new WellnessContext(
            $user,
            $currentMood,
            $history,
            $completedActivities,
            $orders,
            $connections,
            $appointments,
            $streak,
            $reputation
        );

        // 3. Process with Wellness Core Services
        $wellness = $this->wellnessEngine->calculate($context);
        $score = $wellness['score'];
        $indicators = $wellness['indicators'];

        $risk = $this->riskDetectionEngine->detect($score);
        
        $recommendations = $this->recommendationEngine->recommend($score, $risk['level'], $indicators);
        $journey = $this->journeyEngine->evaluate($context);
        $alerts = $this->notificationEngine->getAlerts($context);
        $badges = $this->rewardEngine->getBadges($context);
        $challenge = $this->rewardEngine->getDailyChallenge($context);

        return [
            'context' => $context,
            'wellness_score' => $score,
            'risk_level' => $risk['level'],
            'risk_label' => $risk['label'],
            'risk_color' => $risk['color'],
            'risk_guidance' => $risk['guidance'],
            'indicators' => $indicators,
            'recommendations' => $recommendations,
            'journey' => $journey,
            'alerts' => $alerts,
            'badges' => $badges,
            'challenge' => $challenge,
        ];
    }

    /**
     * Build simple guest recommendations based on landing page quick logs.
     */
    public function buildMockForGuest(array $indicators): array
    {
        $wellness = $this->wellnessEngine->calculate($indicators);
        $score = $wellness['score'];
        $inds = $wellness['indicators'];

        $risk = $this->riskDetectionEngine->detect($score);
        $recommendations = $this->recommendationEngine->recommend($score, $risk['level'], $inds);

        return [
            'wellness_score' => $score,
            'risk_level' => $risk['level'],
            'risk_label' => $risk['label'],
            'risk_color' => $risk['color'],
            'risk_guidance' => $risk['guidance'],
            'indicators' => $inds,
            'recommendations' => $recommendations,
        ];
    }
}
