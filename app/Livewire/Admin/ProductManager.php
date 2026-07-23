<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';

    public bool   $confirmingDelete = false;
    public ?int   $deletingId       = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleFeatured(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => ! $product->is_featured]);
        $this->dispatch('notify', type: 'success', message: 'Produit mis à jour.');
    }

    public function toggleStatus(int $id): void
    {
        $product = Product::findOrFail($id);
        $newStatus = $product->status === 'active' ? 'archived' : 'active';
        $product->update(['status' => $newStatus]);
        $this->dispatch('notify', type: 'success', message: 'Statut produit mis à jour.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId       = $id;
        $this->confirmingDelete = true;
    }

    public function deleteProduct(): void
    {
        Product::findOrFail($this->deletingId)->delete();
        $this->confirmingDelete = false;
        $this->dispatch('notify', type: 'success', message: 'Produit supprimé.');
    }

    public function render()
    {
        $products = Product::with('seller')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhereHas('seller', fn ($u) => $u->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => Product::count(),
            'active'   => Product::active()->count(),
            'featured' => Product::featured()->count(),
        ];

        return view('livewire.admin.product-manager', compact('products', 'stats'))
            ->layout('components.layouts.app');
    }
}
