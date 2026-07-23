<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\MoodEntry;
use Illuminate\Support\Facades\Auth;

class MoodTracker extends Component
{
    public $mood_score = 3; // 1 to 5
    public $emotion = 'neutral';
    public $stress_level = 5; // 1 to 10
    public $sleep_quality = 5; // 1 to 10
    public $energy_level = 5; // 1 to 10
    public $note = '';

    protected $rules = [
        'mood_score' => 'required|integer|min:1|max:5',
        'emotion' => 'required|string|max:50',
        'stress_level' => 'required|integer|min:1|max:10',
        'sleep_quality' => 'required|integer|min:1|max:10',
        'energy_level' => 'required|integer|min:1|max:10',
        'note' => 'nullable|string|max:1000',
    ];

    public function submitMood()
    {
        $this->validate();

        MoodEntry::create([
            'user_id' => Auth::id(),
            'mood_score' => $this->mood_score,
            'emotion' => $this->emotion,
            'stress_level' => $this->stress_level,
            'sleep_quality' => $this->sleep_quality,
            'energy_level' => $this->energy_level,
            'note' => $this->note,
        ]);

        $this->reset(['note', 'stress_level', 'sleep_quality', 'energy_level']);
        $this->mood_score = 3;
        $this->emotion = 'neutral';
        session()->flash('message', 'Mood logged successfully.');
    }

    public function render()
    {
        $moodHistory = MoodEntry::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        // Prepare data for ApexCharts
        $chartData = $moodHistory->reverse()->map(function ($entry) {
            return [
                'x' => $entry->created_at->format('M d'),
                'mood' => $entry->mood_score * 2, // Scale up to 10 for comparison
                'stress' => $entry->stress_level ?? 5,
                'sleep' => $entry->sleep_quality ?? 5,
                'energy' => $entry->energy_level ?? 5,
            ];
        })->values()->toArray();

        return view('livewire.patient.mood-tracker', [
            'moodHistory' => $moodHistory,
            'chartData' => json_encode($chartData),
        ]);
    }
}
