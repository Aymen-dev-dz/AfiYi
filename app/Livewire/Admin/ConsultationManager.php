<?php

namespace App\Livewire\Admin;

use App\Models\Consultation;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultationManager extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $consultations = Consultation::with(['patient', 'therapistProfile.user'])
            ->when($this->search, function ($q) {
                $q->whereHas('patient', fn ($u) => $u->where('name', 'like', "%{$this->search}%"))
                  ->orWhere('reference', 'like', "%{$this->search}%");
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        $stats = [
            'total'     => Consultation::count(),
            'today'     => Consultation::whereDate('scheduled_at', today())->count(),
            'upcoming'  => Consultation::upcoming()->count(),
            'completed' => Consultation::where('status', 'completed')->count(),
            'revenue'   => Consultation::where('status', 'completed')->sum('price'),
        ];

        return view('livewire.admin.consultation-manager', compact('consultations', 'stats'))
            ->layout('components.layouts.app');
    }
}
