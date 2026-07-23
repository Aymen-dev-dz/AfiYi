<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wellness Indicators & Weights
    |--------------------------------------------------------------------------
    |
    | Define the relative weights for each wellness score indicator.
    | Adding new indicators here allows the WellnessEngine to include them automatically.
    |
    */
    'weights' => [
        'stress' => 3.0, // High stress reduces score
        'sleep' => 3.0,
        'energy' => 2.0,
        'social' => 2.0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risk Thresholds
    |--------------------------------------------------------------------------
    |
    | Maps scores (0-100) to psychological risk levels.
    |
    */
    'risk_levels' => [
        'critical' => [
            'max' => 35,
            'label' => 'Critical',
            'color' => 'red',
        ],
        'high' => [
            'max' => 55,
            'label' => 'High',
            'color' => 'orange',
        ],
        'moderate' => [
            'max' => 75,
            'label' => 'Moderate',
            'color' => 'yellow',
        ],
        'low' => [
            'max' => 100,
            'label' => 'Low',
            'color' => 'green',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gamification / Badge Rules
    |--------------------------------------------------------------------------
    |
    | Unlocked automatically based on mood logs and activity counts.
    |
    */
    'badges' => [
        [
            'id' => 'wellness_explorer',
            'title' => 'Wellness Explorer',
            'icon' => '🏅',
            'description' => 'A loggé son humeur 7 fois au total.',
            'rule' => 'mood_logs_count',
            'threshold' => 7,
        ],
        [
            'id' => 'consistency_master',
            'title' => 'Consistency Master',
            'icon' => '🔥',
            'description' => 'A maintenu une série de 3 jours consécutifs.',
            'rule' => 'streak',
            'threshold' => 3,
        ],
        [
            'id' => 'zen_breathe',
            'title' => 'Zen Breather',
            'icon' => '🧘‍♂️',
            'description' => 'A complété un exercice de respiration.',
            'rule' => 'activity_breathing_count',
            'threshold' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activities Configuration
    |--------------------------------------------------------------------------
    |
    | Predefined exercises in the Activities Center.
    |
    */
    'activities' => [
        [
            'id' => 'box_breathing',
            'title' => 'Respiration Carrée',
            'description' => 'Inspirez 4s, retenez 4s, expirez 4s, retenez 4s. Répétez.',
            'category' => 'breathing',
            'duration' => '5 min',
        ],
        [
            'id' => 'sleep_routine',
            'title' => 'Routine du Sommeil',
            'description' => 'Mouvements relaxants et respiration lente de préparation au sommeil.',
            'category' => 'sleep',
            'duration' => '10 min',
        ],
        [
            'id' => 'pomodoro_focus',
            'title' => 'Focus Pomodoro',
            'description' => 'Vagues de 25 minutes de focus suivies de 5 minutes de respiration.',
            'category' => 'focus',
            'duration' => '30 min',
        ],
        [
            'id' => 'pmr',
            'title' => 'Relaxation Musculaire Progressive (PMR)',
            'description' => 'Détendez chaque muscle de votre corps un à un pour relâcher le stress physique.',
            'category' => 'relaxation',
            'duration' => '15 min',
        ],
        [
            'id' => 'walking_meditation',
            'title' => 'Méditation Marchée',
            'description' => 'Synchronisez vos pas avec votre respiration pour ancrer vos sensations.',
            'category' => 'meditation',
            'duration' => '10 min',
        ],
    ],
];
