<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WellnessSpace extends Component
{
    public string $currentTab = 'breathing'; // breathing, meditation, stretching, journal
    
    // Breathing state
    public string $breathingMode = '478'; // 478, box
    
    // Meditation state
    public int $meditationDuration = 3; // minutes
    public string $meditationSound = 'silence'; // rain, ocean, forest, silence
    public bool $isMeditating = false;
    
    // Stretch state
    public int $currentStretchIndex = 0;
    public bool $stretchCompleted = false;
    
    // Journal state
    public string $journalPrompt = '';
    public string $journalContent = '';
    public array $journalEntries = [];

    protected $prompts = [
        "De quoi avez-vous le plus de gratitude aujourd'hui ?",
        "Quelle est la plus petite chose qui vous a fait sourire récemment ?",
        "Qu'est-ce qui vous préoccupe et que vous aimeriez relâcher ?",
        "Qu'avez-vous accompli aujourd'hui dont vous êtes fier ?"
    ];

    public function mount()
    {
        $this->journalPrompt = $this->prompts[array_rand($this->prompts)];
        $this->journalEntries = session('wellness_journals', []);
    }

    public function selectTab(string $tab)
    {
        $this->currentTab = $tab;
    }

    public function saveJournalEntry()
    {
        $this->validate([
            'journalContent' => 'required|string|max:2000',
        ]);

        $entry = [
            'id' => uniqid(),
            'date' => now()->format('d/m/Y H:i'),
            'prompt' => $this->journalPrompt,
            'content' => $this->journalContent,
        ];

        $this->journalEntries[] = $entry;
        session(['wellness_journals' => $this->journalEntries]);

        $this->journalContent = '';
        $this->journalPrompt = $this->prompts[array_rand($this->prompts)];
        
        session()->flash('journal_success', 'Votre réflexion a été enregistrée avec succès.');
    }

    public function deleteJournalEntry(string $id)
    {
        $this->journalEntries = collect($this->journalEntries)
            ->reject(fn($e) => $e['id'] === $id)
            ->values()
            ->toArray();
            
        session(['wellness_journals' => $this->journalEntries]);
    }

    public function render()
    {
        return view('livewire.patient.wellness-space')->layout('layouts.app');
    }
}
