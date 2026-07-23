<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ 
        currentTab: '{{ session('active_tab', 'accueil') }}', 
        searchProduct: '', 
        filterCategory: '', 
        filterTag: '',
        searchTherapist: '',
        filterSpecialty: '',
        filterLang: '',
        maxPrice: 200,
        qrCodeInput: '',
        submitQrCode() {
            if (this.qrCodeInput.trim() !== '') {
                window.location.href = '{{ route('destiny.connect') }}?token=' + this.qrCodeInput;
            }
        }
    }">
        <!-- Top Horizontal Navigation -->
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] p-2 sm:p-3 shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 mb-8 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/10 blur-2xl rounded-full pointer-events-none"></div>
            <nav class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 relative z-10">
                <button @click="currentTab = 'accueil'" :class="currentTab === 'accueil' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60'" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    <span>🏠</span> <span class="hidden sm:inline">Accueil</span>
                </button>
                <button @click="currentTab = 'boutique'" :class="currentTab === 'boutique' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60'" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    <span>🛍️</span> <span class="hidden sm:inline">Boutique</span>
                </button>
                <button @click="currentTab = 'destiny'" :class="currentTab === 'destiny' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60'" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    <span>🚀</span> <span class="hidden sm:inline">Destiny Connection</span>
                </button>
                <button @click="currentTab = 'therapy'" :class="currentTab === 'therapy' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60'" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    <span>🩺</span> <span class="hidden sm:inline">Therapy Hub</span>
                </button>
                <button @click="currentTab = 'journal'" :class="currentTab === 'journal' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/60'" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    <span>📖</span> <span class="hidden sm:inline">Journal & Profil</span>
                </button>
            </nav>
        </div>

        <!-- Dashboard Tabs Content -->
        <div class="max-w-5xl mx-auto space-y-8">

                <!-- 1. ACCUEIL TAB -->
                <div x-show="currentTab === 'accueil'" class="space-y-8" x-transition>
                    <!-- Welcome Hero Header -->
                    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNSkiLz48L3N2Zz4=')] opacity-20"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Bonjour, {{ Auth::user()->name }} !</h1>
                                <p class="text-indigo-100 max-w-xl text-sm leading-relaxed">
                                    Bienvenue dans votre espace d'accompagnement. Prenez un moment aujourd'hui pour écouter vos émotions.
                                </p>
                            </div>
                            <button @click="currentTab = 'destiny'" class="px-5 py-3 bg-white text-indigo-700 font-extrabold rounded-xl shadow-lg hover:scale-105 transition-all text-xs shrink-0 flex items-center gap-2">
                                🚀 Scanner mon QR Code
                            </button>
                        </div>
                    </div>

                    <!-- Mood check widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="text-2xl">😊</span>
                            <h2 class="text-lg font-black text-gray-900 dark:text-white">Comment vous sentez-vous aujourd'hui ?</h2>
                        </div>
                        <livewire:mood-check />
                    </div>

                    <!-- AI Assistant Welcome -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center text-xl shrink-0">🤖</div>
                            <div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white">Assistant IA Wellness</h3>
                                <p class="text-xs text-slate-400">Votre coach bien-être disponible 24/7 pour vous écouter en toute confidentialité.</p>
                            </div>
                        </div>
                        <button @click="$dispatch('toggle-ai-chat')" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl text-xs font-black shadow transition flex items-center justify-center gap-2">
                            <span>💬</span> Ouvrir l'Assistant IA
                        </button>
                    </div>
                </div>

                <!-- 2. BOUTIQUE TAB -->
                <div x-show="currentTab === 'boutique'" class="space-y-6" x-transition style="display: none;">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Marketplace Bien-être</h2>
                            <p class="text-xs text-slate-400 mt-1">Bougies, infusions, huiles essentielles et objets de méditation.</p>
                        </div>
                        <!-- Search and Category Filters -->
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <input type="text" x-model="searchProduct" placeholder="Rechercher un produit..." class="px-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs w-full sm:w-48 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <select x-model="filterCategory" class="px-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs w-full sm:w-40 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-150 dark:border-gray-750 overflow-hidden flex flex-col hover:shadow-md transition-shadow"
                                 x-show="(searchProduct === '' || '{{ strtolower(addslashes($product->name)) }}'.includes(searchProduct.toLowerCase())) && (filterCategory === '' || '{{ addslashes($product->category) }}' === filterCategory)">
                                <div class="h-44 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden relative">
                                    @if($product->main_image)
                                        <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-4xl">🕯️</span>
                                    @endif
                                    <span class="absolute top-3 right-3 bg-indigo-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider shadow">
                                        {{ $product->category }}
                                    </span>
                                </div>
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="font-extrabold text-sm text-gray-900 dark:text-white leading-tight mb-2">{{ $product->name }}</h3>
                                    
                                    @if(is_array($product->tags) && count($product->tags) > 0)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($product->tags as $tag)
                                            <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/40 px-2 py-0.5 rounded-full">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                    @endif

                                    <p class="text-xs text-slate-400 mb-4 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    
                                    <!-- Wellness Benefits -->
                                    @php
                                        $benefits = (isset($product->wellness_benefits) && is_array($product->wellness_benefits)) 
                                            ? $product->wellness_benefits 
                                            : ['Réduit le stress quotidien', 'Favorise la relaxation profonde'];
                                    @endphp
                                    <div class="mb-4 bg-emerald-50/50 dark:bg-emerald-950/20 p-2.5 rounded-xl border border-emerald-100/50 dark:border-emerald-900/30">
                                        <p class="text-[9px] font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                            <span>🌿</span> Bénéfices bien-être
                                        </p>
                                        <div class="flex flex-col gap-1">
                                            @foreach($benefits as $benefit)
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <span class="text-[10px] text-slate-700 dark:text-slate-300 font-medium">{{ $benefit }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Destiny Unlock Banner -->
                                    <div class="bg-indigo-50/50 dark:bg-indigo-950/20 p-2.5 rounded-xl border border-indigo-100/50 dark:border-indigo-900/30 flex items-start gap-2 mb-4">
                                        <span class="text-sm">✨</span>
                                        <p class="text-[10px] text-indigo-700 dark:text-indigo-300 font-semibold leading-snug">
                                            Achetez ce produit pour débloquer votre accès Destiny Connection.
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-500/10">
                                        <span class="text-base font-black text-slate-800 dark:text-white">{{ number_format($product->price, 2) }} €</span>
                                        <form action="{{ route('cart.items.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition transform active:scale-95 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                Ajouter au panier
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. DESTINY CONNECTION TAB -->
                <div x-show="currentTab === 'destiny'" class="space-y-6" x-transition style="display: none;">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-indigo-50 dark:border-indigo-950 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span>🚀</span> Destiny Connection
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Saisissez le code d'activation reçu après votre commande pour lancer une connexion.</p>
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <input type="text" x-model="qrCodeInput" placeholder="Saisir code d'activation..." class="px-4 py-2.5 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-56">
                            <button @click="submitQrCode" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                Activer
                            </button>
                        </div>
                    </div>

                    <!-- Embed Lobby logic -->
                    @if(session('destiny_unlocked'))
                        <livewire:destiny.anonymous-lobby />
                    @else
                        <!-- Not Unlocked Alert banner -->
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/20 dark:to-purple-950/20 border border-indigo-100 dark:border-indigo-900/50 rounded-3xl p-8 text-center space-y-6 max-w-xl mx-auto">
                            <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto shadow-lg text-white text-2xl">
                                🔒
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">Salon Destiny non activé</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                                    Pour échanger avec un compagnon d'écoute bienveillant, vous devez acheter n'importe quel produit dans notre Boutique. Vous recevrez un QR code et un jeton unique pour vous connecter.
                                </p>
                            </div>
                            <button @click="currentTab = 'boutique'" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl shadow-lg transition">
                                Découvrir les produits
                            </button>
                        </div>
                    @endif
                </div>

                <!-- 4. THERAPY HUB TAB -->
                <div x-show="currentTab === 'therapy'" class="space-y-6" x-transition style="display: none;">
                    
                    {{-- Smart Matching AI --}}
                    <livewire:patient.smart-match-results />

                    {{-- Manual Search Section --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Tous les Thérapeutes</h2>
                            <p class="text-xs text-slate-400 mt-1">Parcourez l'annuaire complet de nos psychologues certifiés.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 w-full md:w-auto">
                            <input type="text" x-model="searchTherapist" placeholder="Rechercher par nom..." class="px-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-44">
                            <select x-model="filterSpecialty" class="px-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Toutes spécialités</option>
                                <option value="Burn-out">Burn-out</option>
                                <option value="Anxiété">Anxiété</option>
                                <option value="Dépression">Dépression</option>
                                <option value="Couple">Couple</option>
                            </select>
                            <select x-model="filterLang" class="px-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Toutes langues</option>
                                <option value="French">Français</option>
                                <option value="English">Anglais</option>
                                <option value="Spanish">Espagnol</option>
                            </select>
                        </div>
                    </div>

                    <!-- Therapists Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($therapists as $profile)
                            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-150 dark:border-gray-750 flex flex-col justify-between hover:shadow-md transition"
                                 x-show="(searchTherapist === '' || '{{ strtolower(addslashes($profile->user->name)) }}'.includes(searchTherapist.toLowerCase())) && (filterSpecialty === '' || '{{ addslashes(is_array($profile->specialties) ? implode(',', $profile->specialties) : '') }}'.includes(filterSpecialty)) && (filterLang === '' || '{{ addslashes(is_array($profile->languages) ? implode(',', $profile->languages) : '') }}'.includes(filterLang))">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-100 to-indigo-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center font-extrabold text-xl text-indigo-700 dark:text-indigo-300 shrink-0 shadow-inner">
                                        {{ substr($profile->user->name, 0, 1) }}
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-extrabold text-sm text-gray-900 dark:text-white">{{ $profile->user->name }}</h3>
                                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">{{ $profile->title ?? 'Psychothérapeute' }}</p>
                                        <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed">{{ $profile->bio }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-500/10">
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase">Tarif</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($profile->hourly_rate ?? 60, 2) }} € / h</p>
                                    </div>
                                    <a href="{{ route('teletherapy.profile', $profile->id) }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                        Réserver un créneau
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 text-center text-slate-400 text-xs">
                                Aucun thérapeute disponible pour le moment.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 5. JOURNAL & PROFIL TAB -->
                <div x-show="currentTab === 'journal'" class="space-y-8" x-transition style="display: none;">
                    <!-- Mood history & Trend chart -->
                    <livewire:patient.mood-tracker-chart />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Column 1 -->
                        <div class="space-y-8">
                            <!-- Profil & Préférences -->
                            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                                <div>
                                    <h3 class="font-black text-gray-900 dark:text-white text-base">Profil & Préférences</h3>
                                    <p class="text-[10px] text-slate-400">Gérez vos informations personnelles et vos préférences de partage.</p>
                                </div>
                                
                                <div class="space-y-3 text-xs">
                                    <div class="flex justify-between py-2 border-b border-slate-500/10">
                                        <span class="text-slate-400">Nom :</span>
                                        <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-slate-500/10">
                                        <span class="text-slate-400">Pseudo :</span>
                                        <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->pseudo ?? 'Voyageur' }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-slate-500/10">
                                        <span class="text-slate-400">Email :</span>
                                        <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->email }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-slate-500/10">
                                        <span class="text-slate-400">Pays / Langue :</span>
                                        <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->country ?? 'Algérie' }} ({{ Auth::user()->language ?? 'Français' }})</span>
                                    </div>
                                    
                                    <form action="{{ route('profile.preferences') }}" method="POST" class="pt-2">
                                        @csrf
                                        <div class="flex items-center justify-between">
                                            <label for="share_mood" class="text-slate-400">Partager mon humeur avec mon psy :</label>
                                            <select name="share_mood_with_therapist" id="share_mood" onchange="this.form.submit()" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-[10px] py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="1" {{ Auth::user()->share_mood_with_therapist ? 'selected' : '' }}>Oui</option>
                                                <option value="0" {{ !Auth::user()->share_mood_with_therapist ? 'selected' : '' }}>Non</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>

                            <!-- Badges & Historique -->
                            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                                <div>
                                    <h3 class="font-black text-gray-900 dark:text-white text-base">Badges & Réalisations</h3>
                                    <p class="text-[10px] text-slate-400">Récompenses pour votre parcours bien-être.</p>
                                </div>
                                
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="flex flex-col items-center text-center group cursor-help" title="7 Jours d'affilée - Mood Tracker">
                                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-amber-200 dark:border-amber-800 group-hover:scale-110 transition">🔥</div>
                                        <span class="text-[9px] font-bold mt-2 text-slate-600 dark:text-slate-400">7 Jours</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center group cursor-help" title="Première Consultation">
                                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-blue-200 dark:border-blue-800 group-hover:scale-110 transition">🩺</div>
                                        <span class="text-[9px] font-bold mt-2 text-slate-600 dark:text-slate-400">Thérapie</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center group cursor-help" title="Connexion Destiny">
                                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-purple-200 dark:border-purple-800 group-hover:scale-110 transition">🌌</div>
                                        <span class="text-[9px] font-bold mt-2 text-slate-600 dark:text-slate-400">Destiny</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center group cursor-help opacity-40 grayscale" title="10 Achats Boutique (Verrouillé)">
                                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center text-2xl shadow-sm border border-emerald-200 dark:border-emerald-800 transition">🛍️</div>
                                        <span class="text-[9px] font-bold mt-2 text-slate-600 dark:text-slate-400">Acheteur</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Mood chart moved to top -->

                            <!-- QR Codes Actifs -->
                            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                                <div>
                                    <h3 class="font-black text-gray-900 dark:text-white text-base">QR Codes actifs & Connexions</h3>
                                    <p class="text-[10px] text-slate-400">Vos connexions Destiny obtenues prêtes à être activées.</p>
                                </div>
                                
                                <div class="space-y-3">
                                    @forelse($activeTokens as $tokenItem)
                                        <div class="p-3 bg-indigo-50/50 dark:bg-indigo-950/20 rounded-2xl border border-indigo-100/50 dark:border-indigo-900/30 flex items-center justify-between text-xs">
                                            <div class="space-y-0.5">
                                                <p class="font-bold text-gray-900 dark:text-white">Jeton Destiny</p>
                                                <p class="text-[10px] text-indigo-600 dark:text-indigo-400">{{ substr($tokenItem->destiny_token, 0, 8) }}...</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('destiny.connect', ['token' => $tokenItem->destiny_token]) }}" class="px-2.5 py-1.5 bg-indigo-650 hover:bg-indigo-700 text-white rounded-xl font-bold text-[10px] transition">
                                                    Activer
                                                </a>
                                                <a href="{{ route('destiny.qrcode.download', $tokenItem->destiny_token) }}" target="_blank" class="p-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-650 dark:text-slate-350 rounded-xl transition" title="Télécharger le QR Code">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 text-center py-6">Aucun jeton actif. Achetez un produit pour débloquer un accès ! 🕯️</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Column 2 -->
                        <div class="space-y-8">
                            <!-- Consultations List -->
                            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                                <div>
                                    <h3 class="font-black text-gray-900 dark:text-white text-base">Rendez-vous médicaux</h3>
                                    <p class="text-[10px] text-slate-400">Vos consultations de téléthérapie planifiées et passées.</p>
                                </div>

                                <div class="space-y-3">
                                    @forelse($consultations as $consultation)
                                        <div class="p-3 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-white/30 dark:border-gray-700/50 flex items-center justify-between text-xs hover:bg-white/60 dark:hover:bg-gray-800/60 transition shadow-sm">
                                            <div class="space-y-0.5">
                                                <p class="font-bold text-gray-900 dark:text-white">Psy : {{ $consultation->therapistProfile->user->name }}</p>
                                                <p class="text-[10px] text-slate-400">{{ $consultation->scheduled_at ? $consultation->scheduled_at->format('d/m/Y H:i') : 'Date non planifiée' }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $consultation->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $consultation->status }}
                                                </span>
                                                @if($consultation->status === 'scheduled' || $consultation->status === 'confirmed' || $consultation->status === 'paid')
                                                    <button x-data @click="$dispatch('openPreConsultationChat', { id: {{ $consultation->id }} })" class="relative px-3 py-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-slate-700 dark:text-slate-200 rounded-lg font-bold text-[10px] shadow-sm transition flex items-center gap-1">
                                                        <span>💬</span> Chat
                                                        <livewire:chat-notification-badge :consultation-id="$consultation->id" :key="'badge-p-dash-'.$consultation->id" />
                                                    </button>
                                                    <a href="{{ route('teletherapy.room', $consultation->id) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[10px]">
                                                        Rejoindre
                                                    </a>
                                                @elseif($consultation->status === 'completed')
                                                    @if(!$consultation->review)
                                                        <a href="{{ route('teletherapy.feedback', $consultation->id) }}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-[10px]">
                                                            Laisser un avis
                                                        </a>
                                                    @else
                                                        <span class="text-[10px] text-slate-400 font-bold">★ {{ $consultation->review->rating }}/5</span>
                                                    @endif
                                                @endif
                                                
                                                <!-- Delete Button -->
                                                <form action="{{ route('dashboard.consultations.destroy', $consultation->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet historique ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 opacity-40 hover:opacity-100 text-red-500 hover:text-red-600 transition" title="Supprimer l'historique">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 text-center py-6">Aucune consultation planifiée.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Historique des Conversations Destiny -->
                            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                                <div>
                                    <h3 class="font-black text-gray-900 dark:text-white text-base">Historique des Échanges Destiny</h3>
                                    <p class="text-[10px] text-slate-400">Vos correspondances anonymes passées et en cours.</p>
                                </div>
                                
                                <div class="space-y-3">
                                    @forelse($destinyMatches as $m)
                                        @php
                                            $partner = $m->user_a_id === Auth::id() ? ($m->user_b_nickname ?? 'Anonyme') : ($m->user_a_nickname ?? 'Anonyme');
                                        @endphp
                                        <div class="p-3 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-white/30 dark:border-gray-700/50 flex items-center justify-between text-xs hover:bg-white/60 dark:hover:bg-gray-800/60 transition shadow-sm">
                                            <div class="space-y-0.5">
                                                <p class="font-bold text-gray-900 dark:text-white">Échange avec {{ $partner }}</p>
                                                <p class="text-[10px] text-slate-400">Date : {{ $m->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $m->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                                    {{ $m->status === 'active' ? 'En cours' : 'Fermé' }}
                                                </span>
                                                @if($m->status === 'active')
                                                    <a href="{{ route('destiny.chat', $m->uuid) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[10px]">
                                                        Rejoindre
                                                    </a>
                                                @endif
                                                
                                                <!-- Delete Button -->
                                                <form action="{{ route('dashboard.destiny.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet historique ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 opacity-40 hover:opacity-100 text-red-500 hover:text-red-600 transition" title="Supprimer l'historique">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 text-center py-6">Aucune discussion ouverte.</p>
                                    @endforelse
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Orders List -->
                        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl p-6 rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 space-y-6">
                        <div>
                            <h3 class="font-black text-gray-900 dark:text-white text-base">Historique des Commandes</h3>
                            <p class="text-[10px] text-slate-400">Suivi de vos achats et obtention de vos accès Destiny Connection.</p>
                        </div>

                        <div class="space-y-4">
                            @forelse($orders as $order)
                                <div class="p-4 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-white/30 dark:border-gray-700/50 flex flex-col sm:flex-row justify-between sm:items-center gap-4 text-xs hover:bg-white/60 dark:hover:bg-gray-800/60 transition shadow-sm">
                                    <div class="space-y-1">
                                        <p class="font-black text-gray-900 dark:text-white">Commande #{{ $order->reference }}</p>
                                        <p class="text-[10px] text-slate-400">Date : {{ $order->created_at->format('d/m/Y') }} &bull; Total : {{ number_format($order->total_price, 2) }} €</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <!-- Show Destiny token activation link if present -->
                                        @foreach($order->items as $item)
                                            @if($item->destiny_token)
                                                <a href="{{ route('destiny.connect', ['token' => $item->destiny_token]) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 rounded-xl font-extrabold text-[10px] transition">
                                                    🚀 Activer Destiny
                                                </a>
                                            @endif
                                        @endforeach
                                        
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $order->status }}
                                        </span>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet historique ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 opacity-40 hover:opacity-100 text-red-500 hover:text-red-600 transition" title="Supprimer l'historique">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-6">Aucune commande enregistrée.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

    <!-- Include PreConsultationChat Modal -->
    <livewire:pre-consultation-chat :key="'pre-chat-modal-dash'" />
</x-app-layout>

