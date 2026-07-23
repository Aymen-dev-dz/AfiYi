<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6" x-data>
    <!-- Header & Navigation Tabs -->
    <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl rounded-3xl p-4 shadow-xl border border-slate-200/60 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span>🛍️</span> Espace Vendeur
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Gérez vos produits, commandes et revenus en direct.</p>
        </div>

        <!-- Navigation Tabs -->
        <nav class="flex flex-wrap items-center gap-2">
            <button wire:click="switchTab('dashboard')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $currentTab === 'dashboard' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                📊 Stats & Ventes
            </button>
            <button wire:click="switchTab('catalog')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $currentTab === 'catalog' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                📦 Catalogue
            </button>
            <button wire:click="switchTab('orders')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $currentTab === 'orders' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                🚚 Commandes
            </button>
            <button wire:click="switchTab('finances')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $currentTab === 'finances' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                💰 Finances
            </button>
        </nav>
    </div>

    <!-- Notification Toast -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center justify-between">
            <span>✨ {{ session('message') }}</span>
        </div>
    @endif

    <!-- TAB 1: STATS & VENTES -->
    @if($currentTab === 'dashboard')
        <div wire:key="tab-dashboard" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-6 shadow-md border border-slate-200/50 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ventes (Aujourd'hui)</span>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($stats['sales_today'], 2) }} <span class="text-xs font-bold text-slate-400">DZD</span></h3>
            </div>
            <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-6 shadow-md border border-slate-200/50 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ventes (Ce mois)</span>
                <h3 class="text-3xl font-black text-indigo-600 dark:text-indigo-400 mt-2">{{ number_format($stats['sales_month'], 2) }} <span class="text-xs font-bold text-slate-400">DZD</span></h3>
            </div>
            <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-6 shadow-md border border-slate-200/50 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Commandes Expédiées</span>
                <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $stats['shipped'] }}</h3>
            </div>
            <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-6 shadow-md border border-slate-200/50 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">À Expédier</span>
                <h3 class="text-3xl font-black text-amber-500 mt-2">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    @endif

    <!-- TAB 2: CATALOGUE DES PRODUITS -->
    @if($currentTab === 'catalog')
        <div wire:key="tab-catalog" class="space-y-6">
            <!-- Filter & Action Header -->
            <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-4 shadow-sm border border-slate-200/50 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-1 items-center gap-3 w-full">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full sm:w-72 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500" placeholder="🔍 Rechercher un produit...">
                    
                    <select wire:model.live="filterStatus" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="draft">Brouillon</option>
                        <option value="archived">Archivé</option>
                    </select>
                </div>

                <button wire:click="openCreateModal" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all text-center flex items-center justify-center gap-2">
                    <span>➕</span> Ajouter un Produit
                </button>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-lg border border-slate-200/60 dark:border-slate-800 flex flex-col justify-between group hover:shadow-2xl transition-all">
                        <div class="relative h-48 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <img src="{{ is_string($product->thumbnail) ? asset('storage/'.$product->thumbnail) : ($product->thumbnail_url ?? asset('images/logo.jpg')) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}">
                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $product->status === 'active' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $product->category }}</span>
                                <h4 class="text-base font-black text-slate-900 dark:text-white line-clamp-1 mt-1">{{ $product->name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">{{ $product->description }}</p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($product->price, 2) }} DZD</span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-600 dark:text-slate-300">Stock: {{ $product->quantity }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEditModal({{ $product->id }})" class="flex-1 py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1">
                                        ✏️ Modifier
                                    </button>
                                    <button wire:click="confirmDelete({{ $product->id }})" class="py-2 px-3 bg-red-50 hover:bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 rounded-xl text-xs font-bold transition">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <span class="text-4xl">🛍️</span>
                        <h4 class="text-base font-bold text-slate-700 dark:text-slate-300 mt-2">Aucun produit dans votre catalogue</h4>
                        <p class="text-xs text-slate-400 mt-1 mb-4">Commencez par ajouter votre premier article bien-être.</p>
                        <button wire:click="openCreateModal" class="px-6 py-2.5 bg-indigo-600 text-white font-bold text-xs rounded-xl shadow-md">
                            Ajouter un produit maintenant
                        </button>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- TAB 3: COMMANDES CLIENTS -->
    @if($currentTab === 'orders')
        <div wire:key="tab-orders" class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200/60 dark:border-slate-800 overflow-hidden">
            @if($orders->isEmpty())
                <div class="py-16 text-center text-slate-400 text-xs font-medium">Aucune commande pour le moment.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 uppercase text-[10px] font-black text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Commande</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Statut</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">#{{ $order->reference ?? $order->id }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $order->user->name ?? 'Client' }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="openShipmentModal({{ $order->id }})" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg font-bold text-xs">
                                            Gérer Expédition
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <!-- TAB 4: FINANCES -->
    @if($currentTab === 'finances')
        <div wire:key="tab-finances" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-3xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Solde Disponible</span>
                    <h2 class="text-4xl font-black mt-2">{{ number_format($stats['balance'], 2) }} DZD</h2>
                </div>
                <button {{ $stats['balance'] <= 0 ? 'disabled' : '' }} wire:click="requestPayout" class="w-full py-3 bg-white text-indigo-700 rounded-xl font-bold text-xs shadow-md mt-6 hover:bg-indigo-50 transition">
                    Demander un Virement
                </button>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-lg border border-slate-200 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gains Transférés</span>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($stats['payouts'], 2) }} DZD</h3>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-lg border border-slate-200 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Commissions Plateforme</span>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($stats['commissions'], 2) }} DZD</h3>
            </div>
        </div>
    @endif

    <!-- ── TAILWIND MODAL: AJOUTER / MODIFIER PRODUIT ── -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 animate-in fade-in zoom-in duration-200">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span>{{ $editMode ? '✏️ Modifier le Produit' : '✨ Ajouter un Nouveau Produit' }}</span>
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xl">✕</button>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nom du Produit *</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-900 dark:text-white" placeholder="ex: Tisane Infusion Sérénité">
                            @error('name') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catégorie *</label>
                            <select wire:model="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-900 dark:text-white">
                                <option value="">Sélectionner une catégorie...</option>
                                <option value="Huiles essentielles">Huiles essentielles</option>
                                <option value="Bougies">Bougies</option>
                                <option value="Journaux & Carnets">Journaux & Carnets</option>
                                <option value="Tisanes & Infusions">Tisanes & Infusions</option>
                                <option value="Kits de relaxation">Kits de relaxation</option>
                                <option value="Accessoires de Méditation">Accessoires de Méditation</option>
                                <option value="Soins corporels">Soins corporels</option>
                            </select>
                            @error('category') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Prix (DZD) *</label>
                            <input type="number" step="0.01" wire:model="price" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-900 dark:text-white">
                            @error('price') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Stock Disponible *</label>
                            <input type="number" wire:model="quantity" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-900 dark:text-white">
                            @error('quantity') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Statut *</label>
                            <select wire:model="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-900 dark:text-white">
                                <option value="active">Actif</option>
                                <option value="draft">Brouillon</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description *</label>
                        <textarea wire:model="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-900 dark:text-white" placeholder="Décrivez votre produit en quelques phrases..."></textarea>
                        @error('description') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Image du Produit</label>
                        <input type="file" wire:model="thumbnail" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/30 transition hover:scale-105">
                            {{ $editMode ? 'Enregistrer les Modifications' : 'Créer le Produit' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>
