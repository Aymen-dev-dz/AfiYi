<div x-data="{ 
    open: false, 
    search: '',
    items: [
        // Général
        { name: 'Page d\'accueil', url: '/', cat: 'Général', icon: '🏠', badge: 'Public' },
        { name: 'Tableau de bord Client', url: '/dashboard', cat: 'Général', icon: '📊', badge: 'Patient' },
        
        // Bien-être
        { name: 'Espace Bien-être', url: '/wellness/space', cat: 'Bien-être', icon: '🧘', badge: 'Nouveau' },
        { name: 'Activités & Exercices', url: '/activities', cat: 'Bien-être', icon: '🎯', badge: 'Interactif' },
        { name: 'Offres & Premium', url: '/premium', cat: 'Bien-être', icon: '✨', badge: 'Abonnement' },
        
        // Destiny
        { name: 'Destiny Lobby (Chat Anonyme)', url: '/destiny/lobby', cat: 'Destiny', icon: '🌌', badge: 'Rencontres' },
        { name: 'Scanner QR Code Destiny', url: '/destiny/connect', cat: 'Destiny', icon: '📲', badge: 'QR Connect' },
        
        // Téléthérapie
        { name: 'Annuaire Psychologues', url: '/teletherapy/directory', cat: 'Téléthérapie', icon: '🔍', badge: 'Réservation' },
        { name: 'Mes Consultations (Patient)', url: '/patient/consultations', cat: 'Téléthérapie', icon: '📅', badge: 'Suivi' },
        
        // Marketplace
        { name: 'Boutique Marketplace', url: '/marketplace', cat: 'Marketplace', icon: '🏬', badge: 'Catalogue' },
        { name: 'Mon Panier', url: '/cart', cat: 'Marketplace', icon: '🛒', badge: 'Achat' },
        { name: 'Mes Commandes', url: '/orders', cat: 'Marketplace', icon: '📦', badge: 'Historique' },
        
        // Espace Thérapeute
        { name: 'Consultations Thérapeute', url: '/therapist/consultations', cat: 'Espace Thérapeute', icon: '🩺', badge: 'Pro' },
        { name: 'Planning & Disponibilités', url: '/therapist/schedule', cat: 'Espace Thérapeute', icon: '🕒', badge: 'Pro' },
        { name: 'Mon Profil Professionnel', url: '/therapist/profile', cat: 'Espace Thérapeute', icon: '👨‍⚕️', badge: 'Pro' },
        
        // Espace Vendeur
        { name: 'Gestion des Produits (Vendeur)', url: '/seller/products', cat: 'Espace Vendeur', icon: '🏷️', badge: 'Boutique' },
        { name: 'Commandes Reçues (Vendeur)', url: '/seller/orders', cat: 'Espace Vendeur', icon: '📄', badge: 'Ventes' },
        
        // Administration
        { name: 'Dashboard Administration', url: '/admin/dashboard', cat: 'Administration', icon: '📈', badge: 'Admin' },
        { name: 'Gestion Utilisateurs', url: '/admin/users', cat: 'Administration', icon: '👥', badge: 'Admin' },
        { name: 'Validation Thérapeutes', url: '/admin/therapist-manager', cat: 'Administration', icon: '🛡️', badge: 'Admin' },
        { name: 'Supervision Commandes', url: '/admin/order-manager', cat: 'Administration', icon: '📦', badge: 'Admin' },
        { name: 'Supervision Consultations', url: '/admin/consultation-manager', cat: 'Administration', icon: '📅', badge: 'Admin' },
        { name: 'Catalogue Produits Admin', url: '/admin/product-manager', cat: 'Administration', icon: '🛒', badge: 'Admin' }
    ],
    get filteredItems() {
        if (!this.search.trim()) return this.items;
        const q = this.search.toLowerCase();
        return this.items.filter(i => i.name.toLowerCase().includes(q) || i.cat.toLowerCase().includes(q) || i.url.toLowerCase().includes(q));
    },
    get categories() {
        const cats = [...new Set(this.filteredItems.map(i => i.cat))];
        return cats;
    }
}" 
@keydown.window.prevent.ctrl.k="open = !open" 
@keydown.window.prevent.cmd.k="open = !open"
@open-quick-nav.window="open = true"
class="relative z-[99999]">

    <!-- Floating Trigger Button (Bottom-Right) -->
    <div class="fixed bottom-6 right-6 z-[99999]">
        <button @click="open = true" 
                title="Raccourci: Ctrl + K"
                class="group flex items-center gap-3 px-5 py-3.5 bg-slate-900/95 dark:bg-slate-100/95 hover:bg-slate-900 dark:hover:bg-white text-white dark:text-slate-900 rounded-full shadow-2xl shadow-indigo-500/30 backdrop-blur-xl border border-white/20 dark:border-slate-800/20 font-extrabold text-sm transition-all duration-300 transform hover:scale-105 active:scale-95">
            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs shadow-inner">
                ⚡
            </span>
            <span class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Accès Rapide Interfaces</span>
            </span>
            <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black bg-indigo-500/20 text-indigo-300 dark:text-indigo-700 rounded-md border border-indigo-500/30">
                Ctrl K
            </span>
        </button>
    </div>

    <!-- Modal Drawer Backdrop -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-[99998]" 
         x-cloak></div>

    <!-- Quick Access Modal Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="fixed inset-x-3 bottom-4 top-16 sm:top-auto sm:inset-auto sm:right-6 sm:bottom-20 sm:w-[620px] max-h-[85vh] bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800/80 z-[99999] overflow-hidden flex flex-col"
         x-cloak>
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-transparent shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white text-lg">
                    ⚡
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-base leading-tight">Hub Accès Rapide Tous Rôles</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Basculez entre les rôles & accédez à toutes les interfaces</p>
                </div>
            </div>
            <button @click="open = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-bold text-sm">
                ✕ Esc
            </button>
        </div>

        <!-- Quick Role Switcher Banner -->
        <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 shrink-0">
            <div class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <span>🔑</span> Connexion Rapide Démo (1-Clic Sans Mot De Passe) :
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <a href="/login-as/patient@example.com" 
                   class="flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded-xl border border-emerald-500/20 transition">
                    <span>💚</span> Patient
                </a>
                <a href="/login-as/therapist@example.com" 
                   class="flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold bg-purple-500/10 hover:bg-purple-500/20 text-purple-700 dark:text-purple-300 rounded-xl border border-purple-500/20 transition">
                    <span>🩺</span> Thérapeute
                </a>
                <a href="/login-as/seller@example.com" 
                   class="flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 rounded-xl border border-amber-500/20 transition">
                    <span>🛍️</span> Vendeur
                </a>
                <a href="/login-as/admin@af-iyi.com" 
                   class="flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 rounded-xl border border-indigo-500/20 transition">
                    <span>🛡️</span> Admin
                </a>
            </div>
        </div>

        <!-- Search Bar Input -->
        <div class="px-6 py-3 border-b border-slate-100 dark:border-slate-800 shrink-0">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    🔍
                </span>
                <input type="text" 
                       x-model="search" 
                       placeholder="Rechercher une interface (ex: consultations, admin, panier...)" 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-100 dark:bg-slate-800/80 border-none rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    ✕
                </button>
            </div>
        </div>

        <!-- Links Grid Content -->
        <div class="p-6 overflow-y-auto flex-1 space-y-6 text-slate-800 dark:text-slate-200 custom-scrollbar">

            <template x-for="cat in categories" :key="cat">
                <div class="space-y-2">
                    <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <span x-text="cat"></span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="item in filteredItems.filter(i => i.cat === cat)" :key="item.url">
                            <a :href="item.url" 
                               class="group flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/40 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800/50 transition duration-200">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="text-base" x-text="item.icon"></span>
                                    <div class="truncate">
                                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate" x-text="item.name"></div>
                                        <div class="text-[10px] text-slate-400 truncate" x-text="item.url"></div>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 text-[9px] font-extrabold bg-slate-200/70 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 rounded-md uppercase tracking-wider shrink-0" x-text="item.badge"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="filteredItems.length === 0" class="py-8 text-center text-slate-400 text-xs font-bold">
                Aucune interface trouvée pour "<span x-text="search"></span>"
            </div>

        </div>

        <!-- Footer status -->
        <div class="px-6 py-3 bg-slate-50 dark:bg-slate-950/80 border-t border-slate-100 dark:border-slate-800/80 text-[11px] text-slate-400 dark:text-slate-500 flex justify-between items-center shrink-0">
            <span>AF IYI Platform — All Interfaces</span>
            <span class="font-medium">Total: <strong class="text-slate-700 dark:text-slate-300" x-text="items.length"></strong> interfaces</span>
        </div>

    </div>
</div>
