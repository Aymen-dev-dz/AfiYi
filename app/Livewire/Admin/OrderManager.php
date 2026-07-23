<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';

    public bool   $showStatusModal = false;
    public ?int   $editingOrderId  = null;
    public string $newStatus       = '';
    public string $comment         = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openStatusModal(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $this->editingOrderId = $orderId;
        $this->newStatus      = $order->status;
        $this->comment        = '';
        $this->showStatusModal = true;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'newStatus' => 'required|in:pending,processing,shipped,delivered,completed,cancelled,refunded',
            'comment'   => 'nullable|string|max:500',
        ]);

        $order    = Order::findOrFail($this->editingOrderId);
        $from     = $order->status;
        $order->update(['status' => $this->newStatus]);

        \App\Models\OrderStatusHistory::create([
            'order_id'    => $order->id,
            'user_id'     => auth()->id(),
            'from_status' => $from,
            'to_status'   => $this->newStatus,
            'comment'     => $this->comment ?: 'Statut mis à jour par l\'administration.',
        ]);

        $this->showStatusModal = false;
        $this->dispatch('notify', type: 'success', message: 'Commande mise à jour.');
    }

    public function render()
    {
        $orders = Order::with(['user', 'items'])
            ->when($this->search, function ($q) {
                $q->where('reference', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        $stats = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'revenue'   => Order::whereIn('status', ['completed', 'delivered'])->sum('total_price'),
        ];

        return view('livewire.admin.order-manager', compact('orders', 'stats'))
            ->layout('components.layouts.app');
    }
}
