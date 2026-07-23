<?php

namespace App\Services\Wellness;

use App\Contexts\WellnessContext;

class RewardEngine
{
    /**
     * Determine unlocked badges based on user context and rules in config.
     */
    public function getBadges(WellnessContext $context): array
    {
        $badgeRules = config('wellness.badges', []);
        $unlocked = [];

        $moodLogsCount = $context->history->count();
        $streak = $context->streak;
        $breathingCount = count($context->completedActivities);

        foreach ($badgeRules as $rule) {
            $met = false;
            
            switch ($rule['rule']) {
                case 'mood_logs_count':
                    $met = $moodLogsCount >= $rule['threshold'];
                    break;
                case 'streak':
                    $met = $streak >= $rule['threshold'];
                    break;
                case 'activity_breathing_count':
                    $met = $breathingCount >= $rule['threshold'];
                    break;
            }

            if ($met) {
                $unlocked[] = [
                    'id' => $rule['id'],
                    'title' => $rule['title'],
                    'icon' => $rule['icon'],
                    'description' => $rule['description'],
                ];
            }
        }

        return $unlocked;
    }

    /**
     * Generate daily challenge prompts.
     */
    public function getDailyChallenge(WellnessContext $context): array
    {
        $challenges = [
            'mood_log' => [
                'title' => 'Vérifier son humeur',
                'description' => 'Prenez 30 secondes pour enregistrer comment vous vous sentez aujourd\'hui.',
                'action_label' => 'Log Mood',
                'action_url' => '#mood-form',
            ],
            'breathing' => [
                'title' => 'Moment Zen',
                'description' => 'Complétez une session de respiration guidée de 5 minutes.',
                'action_label' => 'Démarrer',
                'action_url' => route('dashboard'), // activities
            ],
            'destiny_lobby' => [
                'title' => 'Écoute active',
                'description' => 'Rejoignez le Destiny Lobby et connectez-vous avec quelqu\'un.',
                'action_label' => 'Destiny Match',
                'action_url' => route('destiny.lobby'),
            ],
        ];

        // If today has mood entry, suggest breathing. If breathing done, suggest Destiny match.
        if ($context->history->isNotEmpty() && $context->history->first()->created_at->isToday()) {
            if (!empty($context->completedActivities)) {
                return $challenges['destiny_lobby'];
            }
            return $challenges['breathing'];
        }

        return $challenges['mood_log'];
    }
}
