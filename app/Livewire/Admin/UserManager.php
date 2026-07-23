<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $filterRole = '';

    public bool $showModal     = false;
    public ?int  $editingUserId = null;
    public string $selectedRole = '';

    // Ban confirm
    public bool $confirmingToggle = false;
    public ?int  $togglingUserId  = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openRoleModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->selectedRole  = $user->getRoleNames()->first() ?? '';
        $this->showModal     = true;
    }

    public function assignRole(): void
    {
        $user = User::findOrFail($this->editingUserId);

        if ($this->selectedRole) {
            $user->syncRoles([$this->selectedRole]);
        } else {
            $user->syncRoles([]);
        }

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Rôle mis à jour.');
    }

    public function confirmToggle(int $userId): void
    {
        $this->togglingUserId   = $userId;
        $this->confirmingToggle = true;
    }

    public function toggleStatus(): void
    {
        $user = User::findOrFail($this->togglingUserId);
        $user->update(['is_active' => ! ($user->is_active ?? true)]);
        $this->confirmingToggle = false;
        $this->togglingUserId   = null;
        $this->dispatch('notify', type: 'success', message: 'Statut utilisateur mis à jour.');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->when($this->filterRole, function ($q) {
                $q->role($this->filterRole);
            })
            ->latest()
            ->paginate(20);

        $roles = Role::all();

        return view('livewire.admin.user-manager', compact('users', 'roles'))
            ->layout('components.layouts.app');
    }
}
