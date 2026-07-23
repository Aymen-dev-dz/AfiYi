<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            Trouver un Thérapeute
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="w-full lg:w-1/4 shrink-0">
                <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] p-6 shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 relative overflow-hidden sticky top-24">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/10 blur-2xl rounded-full"></div>
                    
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6 relative z-10">Filtres</h3>
                    
                    <form action="{{ route('teletherapy.directory') }}" method="GET" class="relative z-10">
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">Recherche</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, mot-clé..." class="w-full bg-white/50 dark:bg-black/20 border-white/40 dark:border-white/10 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium px-4 py-3 transition">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">Spécialité</label>
                            <select name="specialty" class="w-full bg-white/50 dark:bg-black/20 border-white/40 dark:border-white/10 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium px-4 py-3 transition appearance-none">
                                <option value="">Toutes les spécialités</option>
                                <option value="Anxiety" {{ request('specialty') == 'Anxiety' ? 'selected' : '' }}>Anxiété</option>
                                <option value="Depression" {{ request('specialty') == 'Depression' ? 'selected' : '' }}>Dépression</option>
                                <option value="Trauma" {{ request('specialty') == 'Trauma' ? 'selected' : '' }}>Traumatisme</option>
                                <option value="Relationships" {{ request('specialty') == 'Relationships' ? 'selected' : '' }}>Relations</option>
                                <option value="Burnout" {{ request('specialty') == 'Burnout' ? 'selected' : '' }}>Burnout</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-black py-3.5 px-4 rounded-2xl transition shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Appliquer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Therapist Grid -->
            <div class="w-full lg:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($therapists as $therapist)
                        <div class="bg-white/80 dark:bg-gray-800/60 backdrop-blur-xl rounded-[2rem] shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-white/50 dark:border-white/5 flex flex-col relative group overflow-hidden">
                            
                            <!-- Banner glow -->
                            <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-indigo-500/10 to-transparent"></div>
                            
                            <!-- First Session Free Badge -->
                            @if($therapist->offers_first_free_session)
                            <div class="absolute top-4 right-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full z-10 backdrop-blur-md">
                                1ère Séance Gratuite
                            </div>
                            @endif

                            <div class="p-8 flex flex-col items-center text-center relative z-10">
                                <div class="relative w-28 h-28 mb-5">
                                    <div class="absolute inset-0 bg-gradient-to-tr from-violet-500 to-indigo-500 rounded-full blur group-hover:blur-md transition-all opacity-50"></div>
                                    <div class="relative w-full h-full bg-white dark:bg-gray-900 rounded-full p-1 shadow-inner">
                                        @if($therapist->user->profile_photo_url)
                                            <img src="{{ $therapist->user->profile_photo_url }}" alt="{{ $therapist->user->name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-3xl font-black text-indigo-500">
                                                {{ substr($therapist->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $therapist->user->name }}</h3>
                                <p class="text-indigo-600 dark:text-indigo-400 font-bold text-sm mb-4">{{ $therapist->title ?? 'Psychologue Clinicien' }}</p>
                                
                                <div class="flex flex-wrap justify-center gap-2 my-2">
                                    @if($therapist->specialties)
                                        @foreach(array_slice($therapist->specialties, 0, 3) as $specialty)
                                            <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-[11px] font-bold px-3 py-1.5 rounded-xl border border-indigo-100 dark:border-indigo-800">{{ $specialty }}</span>
                                        @endforeach
                                        @if(count($therapist->specialties) > 3)
                                            <span class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 text-[11px] font-bold px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700">+{{ count($therapist->specialties) - 3 }}</span>
                                        @endif
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mt-4 font-medium leading-relaxed">
                                    {{ $therapist->bio ?? 'Aucune biographie fournie.' }}
                                </p>
                            </div>
                            
                            <div class="mt-auto border-t border-gray-100 dark:border-gray-700/50 p-6 bg-gray-50/50 dark:bg-gray-900/30 flex justify-between items-center relative z-10">
                                <div>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-0.5">Tarif horaire</p>
                                    <span class="font-black text-lg text-gray-900 dark:text-white">{{ number_format($therapist->hourly_rate, 2) }} DZD</span>
                                </div>
                                <a href="{{ route('teletherapy.profile', $therapist->id) }}" class="px-5 py-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-800/40 text-indigo-700 dark:text-indigo-300 text-sm font-black rounded-xl transition-colors border border-indigo-200 dark:border-indigo-800 shadow-sm flex items-center gap-2">
                                    Voir Profil
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white/60 dark:bg-gray-900/40 backdrop-blur-xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-4xl">🔍</span>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Aucun thérapeute trouvé</h3>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Essayez d'ajuster vos critères de recherche.</p>
                            <a href="{{ route('teletherapy.directory') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 text-gray-900 dark:text-white font-bold rounded-xl transition border border-gray-200 dark:border-gray-700 shadow-sm">
                                Effacer les filtres
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10">
                    {{ $therapists->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
