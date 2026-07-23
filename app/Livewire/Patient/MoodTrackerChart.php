<?php

namespace App\Livewire\Patient;

use Livewire\Component;

class MoodTrackerChart extends Component
{
    public array $chartData = [];
    public ?string $aiAnalysis = null;
    public bool $isAnalyzing = false;
    
    public string $period = 'week';
    public int $avgStress = 0;
    public int $avgSleep = 0;
    public int $avgEnergy = 0;
    public int $avgSocial = 0;

    public function mount()
    {
        $this->loadChartData();
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->loadChartData();
        $this->dispatch('chartDataUpdated', data: $this->chartData);
    }

    public function loadChartData()
    {
        $query = \App\Models\MoodEntry::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'asc');

        if ($this->period === 'week') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->period === 'month') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $entries = $query->get();

        if ($entries->isNotEmpty()) {
            $this->avgStress = (int) round($entries->avg('stress_level') * 10); // stress is 1-10, turn to percentage
            $this->avgSleep = (int) round($entries->avg('sleep_quality') * 10);
            $this->avgEnergy = (int) round($entries->avg('energy_level') * 10);
            $this->avgSocial = (int) round($entries->avg('social_level') * 10);
        } else {
            $this->avgStress = $this->avgSleep = $this->avgEnergy = $this->avgSocial = 0;
        }

        $this->chartData = $entries->map(function ($entry) {
            return [
                'date' => $entry->created_at->format('Y-m-d'),
                'score' => $entry->wellness_score ?? ($entry->mood_score * 10),
            ];
        })->toArray();
    }

    public function analyzeTrends()
    {
        $this->isAnalyzing = true;
        $analyzer = app(\App\Services\AI\AiMoodAnalyzer::class);
        
        $entries = \App\Models\MoodEntry::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(14)
            ->get()
            ->toArray();

        $this->aiAnalysis = $analyzer->analyzeTrends($entries);
        $this->isAnalyzing = false;
    }

    public function render()
    {
        return view('livewire.patient.mood-tracker-chart');
    }
}
