<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class ActivityRepository
{
    public function getAll(): Collection
    {
        return collect(config('wellness.activities', $this->getDefaultActivities()));
    }

    public function findById(string $id): ?array
    {
        return $this->getAll()->firstWhere('id', $id);
    }

    public function logCompletion(int $userId, string $activityId): void
    {
        $key = "activity_logs.{$userId}." . date('Y-m-d');
        $logs = Session::get($key, []);
        if (!in_array($activityId, $logs)) {
            $logs[] = $activityId;
            Session::put($key, $logs);
        }
    }

    public function getCompletedToday(int $userId): array
    {
        $key = "activity_logs.{$userId}." . date('Y-m-d');
        return Session::get($key, []);
    }

    private function getDefaultActivities(): array
    {
        return [
            [
                'id' => 'box_breathing',
                'title' => 'Respiration Carrée (Box Breathing)',
                'description' => 'Méthode de respiration 4-4-4-4 utilisée pour calmer le système nerveux.',
                'category' => 'breathing',
                'duration' => '5 min',
            ],
            [
                'id' => 'sleep_routine',
                'title' => 'Routine de Sommeil',
                'description' => 'Exercices légers d\'étirement et de respiration pour préparer le corps à s\'endormir.',
                'category' => 'sleep',
                'duration' => '10 min',
            ],
            [
                'id' => 'pomodoro_focus',
                'title' => 'Pomodoro Focus',
                'description' => 'Travaillez intensément pendant 25 minutes puis accordez-vous 5 minutes de repos.',
                'category' => 'focus',
                'duration' => '30 min',
            ],
            [
                'id' => 'pmr',
                'title' => 'Relaxation Musculaire Progressive (PMR)',
                'description' => 'Contractez puis relâchez chaque groupe musculaire pour dissiper les tensions physiques.',
                'category' => 'relaxation',
                'duration' => '15 min',
            ],
            [
                'id' => 'walking_meditation',
                'title' => 'Méditation de Marche',
                'description' => 'Marchez lentement en vous concentrant pleinement sur vos sensations corporelles.',
                'category' => 'meditation',
                'duration' => '10 min',
            ],
        ];
    }
}
