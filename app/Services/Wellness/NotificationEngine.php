<?php

namespace App\Services\Wellness;

use App\Contexts\WellnessContext;

class NotificationEngine
{
    /**
     * Parse the user context and return active wellness alerts.
     */
    public function getAlerts(WellnessContext $context): array
    {
        $alerts = [];
        $history = $context->history;

        if ($history->count() >= 3) {
            // Check if stress levels are increasing
            $stressTrend = true;
            $prevStress = 0;
            $items = $history->take(3)->reverse()->values();

            for ($i = 0; $i < count($items); $i++) {
                $currentStress = $items[$i]->stress_level ?? 5;
                if ($i > 0 && $currentStress < $prevStress) {
                    $stressTrend = false;
                }
                $prevStress = $currentStress;
            }

            if ($stressTrend && $prevStress >= 6) {
                $alerts[] = [
                    'type' => 'stress_warning',
                    'title' => 'Tendance : Stress en hausse',
                    'message' => 'Votre niveau de stress a augmenté sur vos derniers logs. Prenez 5 minutes pour essayer la respiration carrée.',
                    'action_label' => 'Commencer la respiration',
                    'action_url' => route('dashboard'), // will redirect to activities
                ];
            }
        }

        // Check if user has no peer chats recently
        $lastChat = $context->user->destinyMatches()->where('status', 'closed')->latest('closed_at')->first();
        if ($lastChat && $lastChat->closed_at->diffInDays() > 10) {
            $alerts[] = [
                'type' => 'connection_nudge',
                'title' => 'Compagnon de soutien',
                'message' => 'Vous n\'avez parlé à personne anonymement depuis plus de 10 jours. Échanger peut vous aider à vous sentir mieux.',
                'action_label' => 'Destiny Lobby',
                'action_url' => route('destiny.lobby'),
            ];
        }

        // Checklist reminders
        if ($history->isEmpty() || !$history->first()->created_at->isToday()) {
            $alerts[] = [
                'type' => 'mood_reminder',
                'title' => 'Vérification quotidienne',
                'message' => 'Vous n\'avez pas enregistré votre humeur aujourd\'hui. Prenez 30 secondes pour le faire.',
                'action_label' => 'Log Mood',
                'action_url' => '#mood-form',
            ];
        }

        return $alerts;
    }
}
