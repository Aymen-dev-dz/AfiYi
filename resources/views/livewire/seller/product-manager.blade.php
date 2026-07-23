<div class="row">
    <!-- Header & Tabs -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 mb-0">Espace Vendeur</h2>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <button wire:click="switchTab('dashboard')" class="nav-link {{ $currentTab === 'dashboard' ? 'active fw-bold' : '' }}">Stats & Ventes</button>
            </li>
            <li class="nav-item">
                <button wire:click="switchTab('catalog')" class="nav-link {{ $currentTab === 'catalog' ? 'active fw-bold' : '' }}">Catalogue</button>
            </li>
            <li class="nav-item">
                <button wire:click="switchTab('orders')" class="nav-link {{ $currentTab === 'orders' ? 'active fw-bold' : '' }}">Commandes</button>
            </li>
            <li class="nav-item">
                <button wire:click="switchTab('finances')" class="nav-link {{ $currentTab === 'finances' ? 'active fw-bold' : '' }}">Finances</button>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="col-12">
        
        <!-- 1. STATS & VENTES -->
        @if($currentTab === 'dashboard')
        <div wire:key="tab-dashboard" class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Ventes (Aujourd'hui)</h6>
                        <h3 class="card-text">{{ number_format($stats['sales_today'], 2) }} <small class="text-muted fs-6">DZD</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Ventes (Ce mois)</h6>
                        <h3 class="card-text">{{ number_format($stats['sales_month'], 2) }} <small class="text-muted fs-6">DZD</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Expédiées</h6>
                        <h3 class="card-text">{{ $stats['shipped'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">À Expédier</h6>
                        <h3 class="card-text">{{ $stats['pending'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 2. CATALOGUE -->
        @if($currentTab === 'catalog')
        <div wire:key="tab-catalog">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex gap-2 w-100">
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Rechercher...">
                        <select wire:model.live="filterStatus" class="form-select w-auto">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actif</option>
                            <option value="draft">Brouillon</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                    <button wire:click="openCreateModal" class="btn btn-primary text-nowrap">
                        Ajouter un produit
                    </button>
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="position-relative" style="height: 200px; background-color: #e9ecef;">
                            <img src="{{ is_string($product->thumbnail) ? asset('storage/'.$product->thumbnail) : $product->thumbnail_url }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}">
                            <span class="badge {{ $product->status === 'active' ? 'bg-success' : ($product->status === 'draft' ? 'bg-warning' : 'bg-secondary') }} position-absolute top-0 end-0 m-2">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <small class="text-primary fw-bold">{{ $product->category }}</small>
                            <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                            
                            <div class="mt-auto pt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }} DZD</span>
                                    <span class="badge bg-light text-dark border">Stock: {{ $product->quantity }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button wire:click="openEditModal({{ $product->id }})" class="btn btn-outline-secondary btn-sm flex-grow-1">Modifier</button>
                                    <button wire:click="confirmDelete({{ $product->id }})" class="btn btn-outline-danger btn-sm">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aucun produit dans le catalogue.</p>
                </div>
                @endforelse
            </div>
            
            @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
        @endif

        <!-- 3. COMMANDES -->
        @if($currentTab === 'orders')
        <div wire:key="tab-orders" class="card shadow-sm">
            <div class="card-body p-0">
                @if($orders->isEmpty())
                    <div class="text-center py-5 text-muted">Aucune commande pour le moment.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Paiement</th>
                                    <th>Statut</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="align-middle fw-bold">{{ $order->reference }}</td>
                                    <td class="align-middle">{{ $order->user->name ?? 'Anonyme' }}</td>
                                    <td class="align-middle">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="align-middle">
                                        @php
                                            $notes = json_decode($order->notes, true);
                                            $paymentMethod = $notes['payment_method'] ?? 'card';
                                        @endphp
                                        @if($paymentMethod === 'cod')
                                            <span class="badge bg-info text-dark"><i class="bi bi-cash me-1"></i>À la livraison</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-credit-card me-1"></i>En ligne</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ in_array($order->status, ['delivered', 'completed']) ? 'bg-success' : ($order->status === 'shipped' ? 'bg-primary' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button wire:click="openShipmentModal({{ $order->id }})" class="btn btn-sm btn-outline-primary">Gérer</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                    <div class="card-footer bg-white">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- 4. FINANCES -->
        @if($currentTab === 'finances')
        <div wire:key="tab-finances" class="row g-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="card-title text-white-50">Portefeuille Virtuel (Disponible)</h6>
                            <h2 class="display-6 fw-bold">{{ number_format($stats['balance'], 2) }} DZD</h2>
                        </div>
                        <button {{ $stats['balance'] <= 0 ? 'disabled' : '' }} wire:click="requestPayout" class="btn btn-light w-100 mt-4 fw-bold">
                            Demander un virement
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Gains déjà versés</h6>
                        <h2 class="card-text fw-bold">{{ number_format($stats['payouts'], 2) }} DZD</h2>
                        <small class="text-muted">Total transféré sur votre compte.</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Frais de Plateforme</h6>
                        <h2 class="card-text fw-bold">{{ number_format($stats['commissions'], 2) }} DZD</h2>
                        <small class="text-muted">Commissions AF·IYI.</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Create/Edit Product Modal (Bootstrap version) --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Modifier le produit' : 'Ajouter un produit' }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom *</label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Catégorie *</label>
                                <select wire:model="category" class="form-select">
                                    <option value="">Sélectionner...</option>
                                    <option>Huiles essentielles</option>
                                    <option>Bougies</option>
                                    <option>Journaux & Carnets</option>
                                    <option>Tisanes & Infusions</option>
                                    <option>Kits de relaxation</option>
                                    <option>Accessoires de Méditation</option>
                                    <option>Soins corporels</option>
                                </select>
                                @error('category') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Bénéfices Bien-être</label>
                                <div class="p-3 bg-light rounded border">
                                    <div class="row g-2">
                                        @foreach(['Relaxation', 'Sommeil', 'Énergie', 'Concentration', 'Anti-stress', 'Détox'] as $benefit)
                                            <div class="col-6 col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" wire:model="wellness_benefits" value="{{ $benefit }}" id="ben_{{ $loop->index }}">
                                                    <label class="form-check-label" for="ben_{{ $loop->index }}">{{ $benefit }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix (DZD) *</label>
                                <input type="number" step="0.01" wire:model="price" class="form-control">
                                @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Stock *</label>
                                <input type="number" wire:model="quantity" class="form-control">
                                @error('quantity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Statut</label>
                                <select wire:model="status" class="form-select">
                                    <option value="active">Actif</option>
                                    <option value="draft">Brouillon</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Image</label>
                                <input type="file" wire:model="thumbnail" class="form-control">
                                @if ($thumbnail) 
                                    <img src="{{ is_string($thumbnail) ? asset('storage/'.$thumbnail) : $thumbnail->temporaryUrl() }}" class="mt-2 rounded img-thumbnail" style="height: 100px;"> 
                                @endif
                                @error('thumbnail') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Description *</label>
                                <textarea wire:model="description" rows="3" class="form-control"></textarea>
                                @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Shipment Modal (Bootstrap) --}}
    @if($selectedOrderId)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="updateOrderStatus">
                    <div class="modal-header">
                        <h5 class="modal-title">Gérer l'expédition</h5>
                        <button type="button" class="btn-close" wire:click="$set('selectedOrderId', null)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <select wire:model="shippingStatus" class="form-select">
                                <option value="processing">En traitement</option>
                                <option value="shipped">Expédiée</option>
                                <option value="delivered">Livrée</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Numéro de suivi</label>
                            <input type="text" wire:model="trackingNumber" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('selectedOrderId', null)">Fermer</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal (Bootstrap) --}}
    @if($confirmingDelete)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet article définitivement ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Annuler</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteProduct">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
