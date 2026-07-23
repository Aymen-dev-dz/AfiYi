<div class="space-y-6">
    {{-- Header & Filters --}}
    <div class="bg-gradient-to-br from-violet-600 via-indigo-600 to-blue-600 rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMiIgZmlsbD0id2hpdGUiLz48L3N2Zz4=')]"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">🤖</div>
                <div>
                    <h2 class="text-lg font-black tracking-tight">Smart Matching IA</h2>
                    <p class="text-indigo-100 text-xs">Notre IA analyse votre profil émotionnel pour vous recommander les meilleurs thérapeutes.</p>
                </div>
            </div>

            {{-- Concern Pills --}}
            <div class="mb-4">
                <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider mb-2">Qu'est-ce qui vous préoccupe ?</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['stress' => '😰 Stress', 'anxiety' => '😟 Anxiété', 'depression' => '😔 Dépression', 'sleep' => '😴 Sommeil', 'relationships' => '💔 Relations', 'work' => '💼 Travail', 'loneliness' => '🫂 Solitude', 'trauma' => '🌪️ Trauma', 'self-esteem' => '🪞 Estime de soi'] as $key => $label)
                        <button wire:click="toggleConcern('{{ $key }}')" 
                            class="px-3 py-1.5 rounded-full text-xs font-bold transition-all {{ in_array($key, $concerns) ? 'bg-white text-indigo-700 shadow-lg scale-105' : 'bg-white/15 text-white hover:bg-white/25 border border-white/20' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Filters Row --}}
            <div class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider block mb-1">Langue préférée</label>
                    <select wire:model="preferredLanguage" class="bg-white/15 border border-white/20 text-white rounded-xl text-xs py-2 px-3 focus:ring-white/30 focus:border-white/40 backdrop-blur">
                        <option value="">Toutes</option>
                        <option value="French">🇫🇷 Français</option>
                        <option value="English">🇬🇧 Anglais</option>
                        <option value="Spanish">🇪🇸 Espagnol</option>
                        <option value="Arabic">🇸🇦 Arabe</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider block mb-1">Budget max / séance</label>
                    <input type="number" wire:model="maxPrice" min="0" max="500" step="10" 
                        class="bg-white/15 border border-white/20 text-white rounded-xl text-xs py-2 px-3 w-28 focus:ring-white/30 focus:border-white/40">
                </div>
                <button wire:click="findMatches" class="px-5 py-2 bg-white text-indigo-700 font-black text-xs rounded-xl shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Trouver mon thérapeute
                </button>
            </div>
        </div>
    </div>

    {{-- Results --}}
    @if($hasSearched)
        @if(count($results) === 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-10 text-center border border-gray-100 dark:border-gray-700">
                <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">🔍</div>
                <h3 class="font-black text-gray-900 dark:text-white mb-1">Aucun thérapeute trouvé</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Essayez d'ajuster vos critères de recherche ou votre budget.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($results as $index => $match)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-lg transition-all group relative">
                        {{-- Match Badge --}}
                        <div class="absolute top-3 right-3 z-10">
                            <div class="px-2.5 py-1 rounded-full text-[10px] font-black shadow-lg
                                {{ $match['matchPercent'] >= 80 ? 'bg-green-500 text-white' : ($match['matchPercent'] >= 60 ? 'bg-amber-500 text-white' : 'bg-indigo-500 text-white') }}">
                                {{ $match['matchPercent'] }}% match
                            </div>
                        </div>

                        {{-- Rank Badge --}}
                        @if($index === 0)
                            <div class="absolute top-3 left-3 z-10 w-8 h-8 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center text-white text-xs font-black shadow-lg">
                                🥇
                            </div>
                        @elseif($index === 1)
                            <div class="absolute top-3 left-3 z-10 w-8 h-8 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center text-white text-xs font-black shadow-lg">
                                🥈
                            </div>
                        @elseif($index === 2)
                            <div class="absolute top-3 left-3 z-10 w-8 h-8 bg-gradient-to-br from-amber-600 to-amber-800 rounded-full flex items-center justify-center text-white text-xs font-black shadow-lg">
                                🥉
                            </div>
                        @endif

                        {{-- Profile Header --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-indigo-950/30 dark:to-violet-950/30 p-6 pt-10 flex flex-col items-center text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-2xl shadow-lg mb-3 ring-4 ring-white dark:ring-gray-800">
                                @if($match['photo'])
                                    <img src="{{ Storage::url($match['photo']) }}" alt="{{ $match['name'] }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ substr($match['name'], 0, 1) }}
                                @endif
                            </div>
                            <h3 class="font-black text-gray-900 dark:text-white text-sm">{{ $match['name'] }}</h3>
                            <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mt-0.5">{{ $match['title'] }}</p>
                            
                            {{-- Rating --}}
                            @if($match['rating'])
                                <div class="flex items-center gap-1 mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= round($match['rating']) ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="text-[10px] text-gray-500 ml-1">({{ $match['total_reviews'] }} avis)</span>
                                </div>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-5 space-y-4">
                            {{-- Match Reasons --}}
                            <div class="space-y-1.5">
                                @foreach($match['reasons'] as $reason)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $reason }}</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Specialties --}}
                            @if(!empty($match['specialties']))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($match['specialties'], 0, 3) as $spec)
                                        <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/40 px-2 py-0.5 rounded-full">{{ $spec }}</span>
                                    @endforeach
                                    @if(count($match['specialties']) > 3)
                                        <span class="text-[9px] font-bold text-gray-400 px-1">+{{ count($match['specialties']) - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            {{-- Languages --}}
                            @if(!empty($match['languages']))
                                <p class="text-[10px] text-gray-400"><span class="font-bold">Langues :</span> {{ implode(', ', $match['languages']) }}</p>
                            @endif

                            {{-- Price & CTA --}}
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-base font-black text-gray-900 dark:text-white">{{ number_format($match['price'], 0) }} DZD<span class="text-[10px] font-normal text-gray-400"> / séance</span></p>
                                    @if($match['free_first'])
                                        <p class="text-[10px] font-bold text-green-600 dark:text-green-400">🎁 1ère séance gratuite</p>
                                    @endif
                                </div>
                                <a href="{{ route('teletherapy.profile', $match['id']) }}" 
                                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition transform active:scale-95">
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
