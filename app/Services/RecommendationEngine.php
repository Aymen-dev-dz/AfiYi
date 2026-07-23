<?php

namespace App\Services;

use App\Models\User;

class RecommendationEngine
{
    /**
     * Get personalized recommendations for the user based on their mood and activity.
     */
    public function getRecommendations(User $user)
    {
        // For now, return basic recommendations to prevent the dashboard from crashing.
        // This can be expanded to fetch real Marketplace products or Articles.
        return collect([
            [
                'title' => 'Méditation Guidée - 10 min',
                'description' => 'Prenez un instant pour vous recentrer et apaiser votre esprit.',
                'link' => '#',
                'type' => 'content',
                'icon' => '🧘',
            ],
            [
                'title' => 'Trouver un Thérapeute',
                'description' => 'Découvrez nos experts pour vous accompagner cette semaine.',
                'link' => route('teletherapy.directory'),
                'type' => 'therapist',
                'icon' => '💬',
            ]
        ]);
    }
}
