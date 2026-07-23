<?php

namespace App\Services\Wellness;

use App\Contexts\WellnessContext;

class RiskDetectionEngine
{
    /**
     * Detect risk level and guidance based on score.
     */
    public function detect(int $score): array
    {
        $levels = config('wellness.risk_levels', [
            'critical' => ['max' => 35, 'label' => 'Critical', 'color' => 'red'],
            'high' => ['max' => 55, 'label' => 'High', 'color' => 'orange'],
            'moderate' => ['max' => 75, 'label' => 'Moderate', 'color' => 'yellow'],
            'low' => ['max' => 100, 'label' => 'Low', 'color' => 'green'],
        ]);

        $currentLevel = 'low';
        foreach ($levels as $key => $level) {
            if ($score <= $level['max']) {
                $currentLevel = $key;
                break;
            }
        }

        return [
            'level' => $currentLevel,
            'label' => $levels[$currentLevel]['label'],
            'color' => $levels[$currentLevel]['color'],
            'guidance' => $this->getGuidance($currentLevel),
        ];
    }

    private function getGuidance(string $level): array
    {
        // Purely advisory / guidance output. Never makes a clinical diagnosis.
        $resources = [
            'hotline' => '988 (Ligne d\'aide en cas de crise)',
            'emergency' => '112 (Urgences Médicales Européennes)',
        ];

        return match ($level) {
            'critical' => [
                'text' => 'Attention : Votre score indique une détresse critique. Nous vous recommandons de contacter immédiatement un professionnel ou une ligne d\'urgence.',
                'action_label' => 'Contacter un Professionnel',
                'action_url' => route('teletherapy.directory'),
                'escalate' => true,
                'resources' => $resources,
            ],
            'high' => [
                'text' => 'Votre niveau de stress ou d\'anxiété est élevé. Parler à quelqu\'un ou réserver une séance de téléthérapie pourrait vous aider à relâcher la pression.',
                'action_label' => 'Voir les Psychologues',
                'action_url' => route('teletherapy.directory'),
                'escalate' => true,
                'resources' => null,
            ],
            'moderate' => [
                'text' => 'Vous traversez une période de fatigue ou de stress modéré. Essayez des exercices de respiration guidée ou discutez avec un compagnon de soutien.',
                'action_label' => 'Lancer une Discussion',
                'action_url' => route('destiny.lobby'),
                'escalate' => false,
                'resources' => null,
            ],
            default => [
                'text' => 'Votre bien-être est stable. Continuez à prendre soin de vous et à pratiquer vos exercices favoris.',
                'action_label' => 'Parcourir les Exercices',
                'action_url' => route('dashboard'), // will point to activities center
                'escalate' => false,
                'resources' => null,
            ],
        };
    }
}
