<?php

namespace App\Services\Wellness;

use App\Contexts\WellnessContext;

class JourneyEngine
{
    /**
     * Compute progress timeline and next best action.
     */
    public function evaluate(WellnessContext $context): array
    {
        $hasMood = $context->history->isNotEmpty();
        $hasBreathing = !empty($context->completedActivities);
        $hasProduct = $context->orders->isNotEmpty();
        
        // Peer chats
        $hasChat = $context->user->destinyMatches()->exists();
        
        // Appointments
        $hasAppointment = $context->appointments->isNotEmpty();
        
        // Check if patient completed feedback reviews
        $hasFeedback = \App\Models\TherapistReview::where('patient_id', $context->user->id)->exists();

        $steps = [
            [
                'id' => 'mood_check',
                'label' => 'Mood Check',
                'completed' => $hasMood,
                'description' => 'Enregistrez votre état émotionnel au quotidien.',
            ],
            [
                'id' => 'breathing',
                'label' => 'Respiration',
                'completed' => $hasBreathing,
                'description' => 'Faites un exercice de respiration de 5 minutes.',
            ],
            [
                'id' => 'product',
                'label' => 'Produit',
                'completed' => $hasProduct,
                'description' => 'Prenez soin de vous avec un produit bien-être.',
            ],
            [
                'id' => 'discussion',
                'label' => 'Discussion',
                'completed' => $hasChat,
                'description' => 'Échangez anonymement avec un compagnon.',
            ],
            [
                'id' => 'consultation',
                'label' => 'Consultation',
                'completed' => $hasAppointment,
                'description' => 'Consultez un thérapeute certifié en ligne.',
            ],
            [
                'id' => 'suivi',
                'label' => 'Suivi / Feedback',
                'completed' => $hasFeedback,
                'description' => 'Laissez un avis après votre consultation.',
            ],
        ];

        // Find current step
        $currentStep = 'mood_check';
        $nextBestAction = 'Faites votre premier Mood Check pour commencer !';

        foreach ($steps as $step) {
            if (!$step['completed']) {
                $currentStep = $step['id'];
                $nextBestAction = match ($step['id']) {
                    'mood_check' => 'Enregistrez votre humeur aujourd\'hui.',
                    'breathing' => 'Pratiquez 5 minutes de cohérence cardiaque dans l\'Espace Activités.',
                    'product' => 'Découvrez les huiles et bougies adaptées dans la Marketplace.',
                    'discussion' => 'Débloquez et scannez un QR Code Destiny pour discuter anonymement.',
                    'consultation' => 'Prenez rendez-vous avec un psychologue certifié.',
                    'suivi' => 'Partagez votre retour d\'expérience sur votre dernière séance de thérapie.',
                    default => 'Continuez à prendre soin de vous au quotidien.',
                };
                break;
            }
        }

        return [
            'steps' => $steps,
            'current_step' => $currentStep,
            'next_best_action' => $nextBestAction,
        ];
    }
}
