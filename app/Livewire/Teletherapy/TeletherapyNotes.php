<?php

namespace App\Livewire\Teletherapy;

use App\Models\Consultation;
use App\Models\ConsultationNote;
use Livewire\Component;

class TeletherapyNotes extends Component
{
    public Consultation $consultation;
    public string $newNote = '';
    public $notes;

    public function mount(Consultation $consultation)
    {
        $this->consultation = $consultation;
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = $this->consultation->notes()->latest()->get();
    }

    public function saveNote()
    {
        if (auth()->user()->cannot('takeNotes', $this->consultation)) {
            abort(403);
        }

        $this->validate([
            'newNote' => 'required|string|min:2',
        ]);

        $note = $this->consultation->notes()->create([
            'therapist_profile_id' => auth()->user()->therapistProfile->id,
            'visibility' => 'private',
            'is_session_summary' => false,
        ]);

        $note->setContent($this->newNote);

        $this->newNote = '';
        $this->loadNotes();
        
        session()->flash('message', 'Note saved securely.');
    }

    public function render()
    {
        return view('livewire.teletherapy.teletherapy-notes');
    }
}
