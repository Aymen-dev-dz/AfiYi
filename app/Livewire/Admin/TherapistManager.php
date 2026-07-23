<?php

namespace App\Livewire\Admin;

use App\Models\TherapistProfile;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class TherapistManager extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';

    public bool    $showModal       = false;
    public ?int    $selectedId      = null;
    public string  $rejectionReason = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function approve(int $id): void
    {
        $profile = TherapistProfile::findOrFail($id);
        $profile->update(['status' => 'approved', 'approved_at' => now()]);
        $this->dispatch('notify', type: 'success', message: 'Thérapeute approuvé.');
    }

    public function openRejectModal(int $id): void
    {
        $this->selectedId      = $id;
        $this->rejectionReason = '';
        $this->showModal       = true;
    }

    public function reject(): void
    {
        $this->validate(['rejectionReason' => 'required|string|min:10']);

        TherapistProfile::findOrFail($this->selectedId)->update([
            'status'           => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Thérapeute rejeté.');
    }

    public function suspend(int $id): void
    {
        TherapistProfile::findOrFail($id)->update(['status' => 'suspended']);
        $this->dispatch('notify', type: 'warning', message: 'Thérapeute suspendu.');
    }

    public function render()
    {
        $therapists = TherapistProfile::with('user')
            ->when($this->search, function ($q) {
                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                );
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.therapist-manager', compact('therapists'))
            ->layout('components.layouts.app');
    }
}
