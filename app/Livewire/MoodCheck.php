<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MoodEntry;
use Illuminate\Support\Facades\Auth;

class MoodCheck extends Component
{
    public $step = 1;

    // Questionnaire Answers
    public $feeling = ''; // Great, Good, Okay, Sad, Overwhelmed
    public $affecting = []; // Array: Stress, Anxiety, Loneliness, Work/Studies, Relationships, Sleep
    public $energyLevel = ''; // High, Medium, Low
    public $sleepQuality = ''; // Yes, Somewhat, No

    // Results (Snapshot)
    public $snapshot = [];
    public $recommendedTags = [];
    public $advice = '';
    public bool $showBreathingExercise = false;

    public function selectFeeling($value)
    {
        $this->feeling = $value;
        $this->step = 2;
    }

    public function toggleAffecting($value)
    {
        if (in_array($value, $this->affecting)) {
            $this->affecting = array_diff($this->affecting, [$value]);
        } else {
            $this->affecting[] = $value;
        }
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function selectEnergy($value)
    {
        $this->energyLevel = $value;
        $this->step = 4;
    }

    public function selectSleep($value)
    {
        $this->sleepQuality = $value;
        $this->analyzeMood();
    }

    public function startBreathing()
    {
        $this->showBreathingExercise = true;
    }

    public function closeBreathing()
    {
        $this->showBreathingExercise = false;
    }

    public function analyzeMood()
    {
        // Calculate dynamic snapshot percentages
        $stressLevel = 30; // base
        $energyPercent = 50;
        $moodScore = 5; // 1 to 10 for DB

        // Mood base
        switch ($this->feeling) {
            case 'Great': $moodScore = 10; $stressLevel -= 20; break;
            case 'Good': $moodScore = 8; $stressLevel -= 10; break;
            case 'Okay': $moodScore = 6; break;
            case 'Sad': $moodScore = 3; $stressLevel += 20; break;
            case 'Overwhelmed': $moodScore = 1; $stressLevel += 40; break;
        }

        // Affecting factors
        if (in_array('Stress', $this->affecting) || in_array('Work/Studies', $this->affecting)) {
            $stressLevel += 30;
        }
        if (in_array('Anxiety', $this->affecting)) {
            $stressLevel += 20;
        }

        // Constraints
        $stressLevel = min(100, max(0, $stressLevel));

        // Energy
        switch ($this->energyLevel) {
            case 'High': $energyPercent = 90; break;
            case 'Medium': $energyPercent = 50; break;
            case 'Low': $energyPercent = 15; break;
        }

        // Populate Snapshot
        $this->snapshot = [
            'stress' => $stressLevel,
            'energy' => $energyPercent,
            'sleep' => $this->sleepQuality, // Yes, Somewhat, No
        ];

        // Recommendations (Needs)
        $this->recommendedTags = [];
        if ($stressLevel > 60) $this->recommendedTags[] = 'Stress Relief';
        if ($this->sleepQuality === 'No' || in_array('Sleep', $this->affecting)) $this->recommendedTags[] = 'Better Sleep';
        if ($this->energyLevel === 'Low') $this->recommendedTags[] = 'Focus & Productivity';
        if (in_array('Anxiety', $this->affecting) || $this->feeling === 'Overwhelmed') $this->recommendedTags[] = 'Emotional Balance';
        if (empty($this->recommendedTags)) $this->recommendedTags[] = 'Self-Care';

        // Advice
        if ($stressLevel > 70 || $this->feeling === 'Overwhelmed') {
            $this->advice = "We noticed you're feeling quite overwhelmed. It's okay to take a step back and breathe. We have resources to help you relax.";
        } elseif ($this->feeling === 'Sad' || in_array('Loneliness', $this->affecting)) {
            $this->advice = "You're not alone. Consider joining our anonymous chat to connect with someone who understands.";
        } else {
            $this->advice = "Thanks for checking in! Keep up the good momentum and don't forget to take time for yourself.";
        }

        // Save to DB
        if (Auth::check()) {
            // Map sleep string to 1-10 scale for DB
            $sleepScore = $this->sleepQuality === 'Yes' ? 9 : ($this->sleepQuality === 'Somewhat' ? 5 : 2);

            $wellnessScore = round(($moodScore * 10 + $energyPercent + (100 - $stressLevel) + $sleepScore * 10) / 4);

            MoodEntry::create([
                'user_id' => Auth::id(),
                'mood_score' => $moodScore,
                'emotion' => $this->feeling,
                'stress_level' => round($stressLevel / 10), // 1 to 10 scale
                'sleep_quality' => $sleepScore,
                'energy_level' => round($energyPercent / 10),
                'wellness_score' => $wellnessScore,
                'note' => implode(', ', $this->affecting),
            ]);
        }

        $this->step = 5; // Results step
    }

    public function resetCheck()
    {
        $this->step = 1;
        $this->feeling = '';
        $this->affecting = [];
        $this->energyLevel = '';
        $this->sleepQuality = '';
    }

    public function render()
    {
        return view('livewire.mood-check');
    }
}
