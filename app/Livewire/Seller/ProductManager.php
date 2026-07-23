<?php

namespace App\Livewire\Seller;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Commission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    // Tab state
    public string $currentTab = 'dashboard'; // dashboard, catalog, orders, finances

    // ── Filters ────────────────────────────────────────────────────
    public string $search    = '';
    public string $filterStatus = '';

    // ── Form ──────────────────────────────────────────────────────
    public bool $showModal   = false;
    public bool $editMode    = false;
    public ?int $editingId   = null;

    public string $name        = '';
    public string $description = '';
    public string $short_description = '';
    public string $category    = '';
    public string $status      = 'active';
    public float  $price       = 0.0;
    public float  $compare_price = 0.0;
    public int    $quantity    = 0;
    public bool   $is_featured = false;
    public $thumbnail;
    public array  $wellness_benefits = [];

    // ── Delete Confirm ─────────────────────────────────────────────
    public bool $confirmingDelete = false;
    public ?int $deletingId       = null;

    // ── Orders ─────────────────────────────────────────────────────
    public ?int $selectedOrderId = null;
    public string $shippingStatus = 'processing';
    public string $trackingNumber = '';

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category'          => 'required|string|max:100',
            'status'            => 'required|in:draft,active,archived',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
            'is_featured'       => 'boolean',
            'thumbnail'         => 'nullable|image|max:2048',
            'wellness_benefits' => 'nullable|array',
            'wellness_benefits.*' => 'string',
        ];
    }

    public function switchTab(string $tab): void
    {
        $this->currentTab = $tab;
        if ($tab === 'catalog') {
            $this->resetPage('products_page');
        } elseif ($tab === 'orders') {
            $this->resetPage('orders_page');
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editMode  = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $this->editingId         = $id;
        $this->name              = $product->name;
        $this->description       = $product->description;
        $this->short_description = $product->short_description ?? '';
        $this->category          = $product->category;
        $this->status            = $product->status;
        $this->price             = (float) $product->price;
        $this->compare_price     = (float) ($product->compare_price ?? 0);
        $this->quantity          = (int) $product->quantity;
        $this->is_featured       = (bool) $product->is_featured;
        $this->wellness_benefits = $product->wellness_benefits ?? [];
        $this->editMode          = true;
        $this->showModal         = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'seller_id'         => Auth::id(),
            'name'              => $this->name,
            'slug'              => Str::slug($this->name) . '-' . Str::random(5),
            'description'       => $this->description,
            'short_description' => $this->short_description,
            'category'          => $this->category,
            'status'            => $this->status,
            'price'             => $this->price,
            'compare_price'     => $this->compare_price ?: null,
            'quantity'          => $this->quantity,
            'is_featured'       => $this->is_featured,
            'wellness_benefits' => $this->wellness_benefits,
        ];

        if ($this->thumbnail) {
            $data['thumbnail'] = $this->thumbnail->store('products', 'public');
        }

        if ($this->editMode && $this->editingId) {
            Product::where('seller_id', Auth::id())->where('id', $this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Produit mis à jour.');
        } else {
            Product::create($data);
            $this->dispatch('notify', type: 'success', message: 'Produit créé avec succès.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId       = $id;
        $this->confirmingDelete = true;
    }

    public function deleteProduct(): void
    {
        Product::where('seller_id', Auth::id())->where('id', $this->deletingId)->delete();
        $this->confirmingDelete = false;
        $this->deletingId       = null;
        $this->dispatch('notify', type: 'success', message: 'Produit supprimé.');
    }

    // ── Orders actions ──────────────────────────────────────────────
    public function openShipmentModal(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $order = Order::findOrFail($orderId);
        $this->shippingStatus = $order->status;
        $this->trackingNumber = '';
    }

    public function updateOrderStatus(): void
    {
        $order = Order::findOrFail($this->selectedOrderId);
        $from = $order->status;
        $order->update(['status' => $this->shippingStatus]);

        \App\Models\OrderStatusHistory::create([
            'order_id'    => $order->id,
            'user_id'     => Auth::id(),
            'from_status' => $from,
            'to_status'   => $this->shippingStatus,
            'comment'     => 'Statut de livraison mis à jour par le vendeur. Numéro de suivi : ' . ($this->trackingNumber ?: 'N/A'),
        ]);

        $this->selectedOrderId = null;
        $this->dispatch('notify', type: 'success', message: 'Statut de commande mis à jour.');
    }

    // ── Finances actions ─────────────────────────────────────────────
    public function requestPayout(): void
    {
        Commission::where('seller_id', Auth::id())
            ->where('status', 'unpaid')
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

        $this->dispatch('notify', type: 'success', message: 'Virement de vos gains initié vers votre compte bancaire !');
    }

    private function resetForm(): void
    {
        $this->editingId         = null;
        $this->name              = '';
        $this->description       = '';
        $this->short_description = '';
        $this->category          = '';
        $this->status            = 'active';
        $this->price             = 0.0;
        $this->compare_price     = 0.0;
        $this->quantity          = 0;
        $this->is_featured       = false;
        $this->thumbnail         = null;
        $this->wellness_benefits = [];
        $this->resetValidation();
    }

    public function render()
    {
        $sellerId = Auth::id();

        // 1. Products query
        $products = Product::forSeller($sellerId)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(12, pageName: 'products_page');

        // 2. Orders query (only orders with this seller's products)
        $orders = Order::forSeller($sellerId)
            ->with(['items' => fn ($q) => $q->where('seller_id', $sellerId), 'user'])
            ->latest()
            ->paginate(10, pageName: 'orders_page');

        // 3. Stats
        $salesToday = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled')->whereDate('created_at', today()))
            ->sum(DB::raw('unit_price * quantity'));

        $salesMonth = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled')->whereMonth('created_at', now()->month))
            ->sum(DB::raw('unit_price * quantity'));

        $shippedCount = Order::forSeller($sellerId)->where('status', 'shipped')->count();
        $pendingCount = Order::forSeller($sellerId)->whereIn('status', ['processing', 'pending'])->count();

        // 4. Commissions / Earnings
        $unpaidBalance = Commission::where('seller_id', $sellerId)->where('status', 'unpaid')->sum('net_amount');
        $totalPayouts = Commission::where('seller_id', $sellerId)->where('status', 'paid')->sum('net_amount');
        $commissionTotal = Commission::where('seller_id', $sellerId)->sum('commission_amount');

        $stats = [
            'total'         => Product::forSeller($sellerId)->count(),
            'active'        => Product::forSeller($sellerId)->active()->count(),
            'sales_today'   => $salesToday,
            'sales_month'   => $salesMonth,
            'shipped'       => $shippedCount,
            'pending'       => $pendingCount,
            'balance'       => $unpaidBalance,
            'payouts'       => $totalPayouts,
            'commissions'   => $commissionTotal,
        ];

        return view('livewire.seller.product-manager', compact('products', 'orders', 'stats'))
            ->layout('layouts.bootstrap');
    }
}
