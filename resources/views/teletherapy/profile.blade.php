<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 dark:text-white leading-tight flex items-center gap-3">
            <a href="{{ route('teletherapy.directory') }}" class="text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 transition flex items-center gap-2 bg-white/50 dark:bg-gray-800/50 px-4 py-2 rounded-xl backdrop-blur-md border border-white/50 dark:border-gray-700 shadow-sm hover:shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Annuaire
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span>{{ $therapist->user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 overflow-hidden relative">
            
            <!-- Header Banner Section -->
            <div class="h-48 md:h-64 bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>
            
            <div class="px-6 md:px-12 pb-16 relative -mt-20 md:-mt-28">
                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Profile Info -->
                    <div class="flex-1">
                        <div class="w-40 h-40 md:w-48 md:h-48 bg-white/20 dark:bg-gray-800/50 backdrop-blur-md rounded-[2.5rem] p-2 shadow-2xl mb-8 relative rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="w-full h-full bg-white dark:bg-gray-900 rounded-[2rem] overflow-hidden flex items-center justify-center shadow-inner">
                                @if($therapist->user->profile_photo_url)
                                    <img src="{{ $therapist->user->profile_photo_url }}" alt="{{ $therapist->user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-6xl font-black text-indigo-500">{{ substr($therapist->user->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-2">
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight">{{ $therapist->user->name }}</h1>
                            @if($therapist->user->email_verified_at)
                                <span class="bg-blue-500 text-white rounded-full p-1 shadow-md shadow-blue-500/30" title="Vérifié">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-xl text-indigo-600 dark:text-indigo-400 font-bold mb-8 uppercase tracking-widest">{{ $therapist->title ?? 'Psychologue Clinicien' }}</p>
                        
                        <div class="bg-white/50 dark:bg-gray-800/30 rounded-3xl p-8 border border-white/50 dark:border-white/5 shadow-sm mb-8">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                                <span class="text-indigo-500">✨</span> À propos
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed font-medium text-lg">
                                {{ $therapist->bio ?? 'Aucune biographie fournie.' }}
                            </p>
                        </div>
                        
                        <div class="bg-white/50 dark:bg-gray-800/30 rounded-3xl p-8 border border-white/50 dark:border-white/5 shadow-sm">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                                <span class="text-indigo-500">🎯</span> Spécialités
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse($therapist->specialties ?? [] as $specialty)
                                    <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-4 py-2 rounded-xl border border-indigo-100 dark:border-indigo-800 font-bold shadow-sm">{{ $specialty }}</span>
                                @empty
                                    <span class="text-gray-500 dark:text-gray-400 font-medium italic">Aucune spécialité renseignée.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Card -->
                    <div class="w-full lg:w-[400px] lg:mt-32">
                        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-2xl rounded-[2rem] p-8 border border-white dark:border-gray-700 shadow-2xl shadow-indigo-900/10 sticky top-24 relative overflow-hidden group">
                            
                            <!-- Card glow effect -->
                            <div class="absolute -right-20 -top-20 w-48 h-48 bg-indigo-500/10 blur-3xl rounded-full group-hover:bg-indigo-500/20 transition-all duration-500"></div>

                            @if($therapist->offers_first_free_session)
                            <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 flex items-start gap-3">
                                <div class="text-2xl">🎁</div>
                                <div>
                                    <p class="text-emerald-700 dark:text-emerald-400 font-bold text-sm">Première séance offerte</p>
                                    <p class="text-emerald-600/70 dark:text-emerald-400/70 text-xs mt-1">Découvrez si ce thérapeute vous correspond sans engagement.</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex justify-between items-end mb-8 pb-8 border-b border-gray-100 dark:border-gray-700 relative z-10">
                                <div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-1">Tarif de la séance</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-4xl font-black text-gray-900 dark:text-white">{{ number_format($therapist->hourly_rate, 2) }}</span>
                                        <span class="text-xl font-bold text-gray-900 dark:text-white">DZD</span>
                                        <span class="text-gray-500 dark:text-gray-400 font-medium ml-1">/ h</span>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="{{ route('teletherapy.book', $therapist->id) }}" method="POST" class="space-y-6 relative z-10">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Type de séance</label>
                                    <select name="type" required class="w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 font-medium appearance-none">
                                        <option value="video">🎥 Appel Vidéo</option>
                                        <option value="audio">📞 Appel Audio</option>
                                        <option value="chat">💬 Chat écrit</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Durée</label>
                                    <select name="duration_minutes" required class="w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 font-medium appearance-none">
                                        <option value="30">30 Minutes</option>
                                        <option value="45">45 Minutes</option>
                                        <option value="60" selected>60 Minutes</option>
                                        <option value="90">90 Minutes</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Date & Heure</label>
                                    <input type="datetime-local" name="scheduled_at" required min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" class="w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 font-medium">
                                </div>
                                
                                <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-black py-4 px-6 rounded-xl shadow-xl shadow-indigo-500/30 transition-all transform hover:scale-[1.02] flex justify-center items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Réserver maintenant
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
