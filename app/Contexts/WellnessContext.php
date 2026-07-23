<?php

namespace App\Contexts;

use App\Models\User;
use App\Models\MoodEntry;
use Illuminate\Support\Collection;

class WellnessContext
{
    public User $user;
    public ?MoodEntry $currentMood = null;
    public Collection $history;
    public array $completedActivities = [];
    public Collection $orders;
    public Collection $connections;
    public Collection $appointments;
    public int $streak = 0;
    public array $reputation = [];
    public array $preferences = [];
    public array $languages = [];

    public function __construct(
        User $user,
        ?MoodEntry $currentMood,
        Collection $history,
        array $completedActivities,
        Collection $orders,
        Collection $connections,
        Collection $appointments,
        int $streak,
        array $reputation
    ) {
        $this->user = $user;
        $this->currentMood = $currentMood;
        $this->history = $history;
        $this->completedActivities = $completedActivities;
        $this->orders = $orders;
        $this->connections = $connections;
        $this->appointments = $appointments;
        $this->streak = $streak;
        $this->reputation = $reputation;
        
        $this->preferences = $user->preferences ?? [];
        $this->languages = $user->languages ?? ['fr', 'en'];
    }
}
