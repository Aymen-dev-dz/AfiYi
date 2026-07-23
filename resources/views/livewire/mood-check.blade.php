<div>
    <div class="relative w-full min-h-[480px]">
        <!-- Dynamic Glow Background -->
        <div class="absolute inset-0 bg-gradient-to-tr from-violet-500 via-indigo-500 to-purple-500 rounded-[3rem] transform rotate-3 scale-105 opacity-20 dark:opacity-30 blur-2xl transition-all duration-1000"></div>
        <div class="absolute inset-0 bg-gradient-to-bl from-cyan-400 to-blue-500 rounded-[3rem] transform -rotate-2 scale-100 opacity-20 dark:opacity-20 blur-xl mix-blend-overlay transition-all duration-1000"></div>
        
        <div class="relative min-h-[500px] w-full bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] border border-white/50 dark:border-white/5 shadow-2xl overflow-hidden flex flex-col p-10">
            
            @if ($step === 1)
            <!-- Step 1: Feeling -->
            <div class="flex-1 flex flex-col justify-center items-center text-center animate-fade-in-up">
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-3 tracking-tight">Bonjour 👋</h3>
                <p class="text-xl font-medium text-gray-600 dark:text-gray-300 mb-10">Comment te sens-tu aujourd'hui ?</p>
                
                <div class="flex flex-col gap-4 w-full max-w-sm">
                    <button wire:click="selectFeeling('Great')" class="w-full p-5 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-emerald-300 dark:hover:border-emerald-500/50 transition-all shadow-sm hover:shadow-lg hover:-translate-y-1 text-left flex items-center gap-5 group">
                        <span class="text-4xl group-hover:scale-125 group-hover:rotate-6 transition-transform">😊</span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">Super</span>
                    </button>
                    <button wire:click="selectFeeling('Good')" class="w-full p-5 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-blue-300 dark:hover:border-blue-500/50 transition-all shadow-sm hover:shadow-lg hover:-translate-y-1 text-left flex items-center gap-5 group">
                        <span class="text-4xl group-hover:scale-125 group-hover:-rotate-6 transition-transform">🙂</span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">Bien</span>
                    </button>
                    <button wire:click="selectFeeling('Okay')" class="w-full p-5 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-yellow-300 dark:hover:border-yellow-500/50 transition-all shadow-sm hover:shadow-lg hover:-translate-y-1 text-left flex items-center gap-5 group">
                        <span class="text-4xl group-hover:scale-125 transition-transform">😐</span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">Moyen</span>
                    </button>
                    <button wire:click="selectFeeling('Sad')" class="w-full p-5 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-purple-300 dark:hover:border-purple-500/50 transition-all shadow-sm hover:shadow-lg hover:-translate-y-1 text-left flex items-center gap-5 group">
                        <span class="text-4xl group-hover:scale-125 transition-transform">😔</span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">Triste</span>
                    </button>
                    <button wire:click="selectFeeling('Overwhelmed')" class="w-full p-5 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-red-300 dark:hover:border-red-500/50 transition-all shadow-sm hover:shadow-lg hover:-translate-y-1 text-left flex items-center gap-5 group">
                        <span class="text-4xl group-hover:scale-125 transition-transform">😣</span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-200">Submergé</span>
                    </button>
                </div>
            </div>

            @elseif ($step === 2)
            <!-- Step 2: Affecting -->
            <div class="flex-1 flex flex-col justify-center items-center text-center animate-fade-in-up">
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Qu'est-ce qui t'affecte le plus ?</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-8 uppercase tracking-widest">Sélection multiple possible</p>
                
                <div class="grid grid-cols-2 gap-4 w-full max-w-md">
                    @php $options = ['Stress' => '😰', 'Anxiété' => '🧠', 'Solitude' => '👤', 'Travail/Études' => '💼', 'Relations' => '❤️', 'Sommeil' => '😴']; @endphp
                    @foreach($options as $val => $emoji)
                        <button wire:click="toggleAffecting('{{ $val }}')" class="p-6 rounded-[1.5rem] transition-all duration-300 shadow-sm border {{ in_array($val, $affecting) ? 'bg-indigo-50 border-indigo-400 dark:bg-indigo-900/50 dark:border-indigo-500 shadow-indigo-500/20 shadow-lg scale-105' : 'bg-white/50 dark:bg-gray-800/50 border-white/50 dark:border-white/5 hover:bg-white dark:hover:bg-gray-700 hover:border-indigo-200' }} flex flex-col items-center gap-3">
                            <span class="text-3xl">{{ $emoji }}</span>
                            <span class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ $val }}</span>
                        </button>
                    @endforeach
                </div>
                
                <button wire:click="nextStep" class="mt-10 px-10 py-4 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-black text-lg rounded-2xl transition-all shadow-xl shadow-indigo-500/30 hover:scale-105">
                    Continuer
                </button>
            </div>

            @elseif ($step === 3)
            <!-- Step 3: Energy -->
            <div class="flex-1 flex flex-col justify-center items-center text-center animate-fade-in-up">
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-10">Quel est ton niveau d'énergie ?</h3>
                
                <div class="flex flex-col gap-4 w-full max-w-sm">
                    <button wire:click="selectEnergy('High')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-yellow-400 transition-all shadow-sm hover:shadow-lg flex justify-between items-center group">
                        <span class="font-black text-gray-800 dark:text-gray-200 text-xl">Élevé</span>
                        <span class="text-4xl group-hover:scale-125 transition-transform drop-shadow-md">⚡</span>
                    </button>
                    <button wire:click="selectEnergy('Medium')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-emerald-400 transition-all shadow-sm hover:shadow-lg flex justify-between items-center group">
                        <span class="font-black text-gray-800 dark:text-gray-200 text-xl">Moyen</span>
                        <span class="text-4xl group-hover:scale-125 transition-transform drop-shadow-md">🔋</span>
                    </button>
                    <button wire:click="selectEnergy('Low')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-red-400 transition-all shadow-sm hover:shadow-lg flex justify-between items-center group">
                        <span class="font-black text-gray-800 dark:text-gray-200 text-xl">Faible</span>
                        <span class="text-4xl group-hover:scale-125 transition-transform drop-shadow-md">🪫</span>
                    </button>
                </div>
            </div>

            @elseif ($step === 4)
            <!-- Step 4: Sleep -->
            <div class="flex-1 flex flex-col justify-center items-center text-center animate-fade-in-up">
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-10">As-tu bien dormi ?</h3>
                
                <div class="flex flex-col gap-4 w-full max-w-sm">
                    <button wire:click="selectSleep('Yes')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-indigo-400 transition-all shadow-sm hover:shadow-lg text-center font-black text-gray-800 dark:text-gray-200 text-xl">
                        Oui, super bien !
                    </button>
                    <button wire:click="selectSleep('Somewhat')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-indigo-400 transition-all shadow-sm hover:shadow-lg text-center font-black text-gray-800 dark:text-gray-200 text-xl">
                        Moyennement
                    </button>
                    <button wire:click="selectSleep('No')" class="w-full p-6 rounded-[1.5rem] bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-700 border border-white/50 dark:border-white/5 hover:border-indigo-400 transition-all shadow-sm hover:shadow-lg text-center font-black text-gray-800 dark:text-gray-200 text-xl">
                        Non, pas vraiment
                    </button>
                </div>
            </div>

            @elseif ($step === 5)
            <!-- Step 5: Results -->
            <div class="flex-1 flex flex-col h-full animate-fade-in-up">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Ton Snapshot</h3>
                    <p class="text-sm font-bold text-gray-500 mt-2 uppercase tracking-widest">Analyse de ton état émotionnel</p>
                </div>

                <!-- Snapshot Stats -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-[1.5rem] border border-white/50 dark:border-white/5 text-center shadow-lg shadow-indigo-900/5">
                        <span class="block text-3xl mb-2 drop-shadow-sm">😰</span>
                        <span class="block text-2xl font-black text-gray-900 dark:text-white">{{ $snapshot['stress'] }}%</span>
                        <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Stress</span>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-[1.5rem] border border-white/50 dark:border-white/5 text-center shadow-lg shadow-indigo-900/5">
                        <span class="block text-3xl mb-2 drop-shadow-sm">⚡</span>
                        <span class="block text-2xl font-black text-gray-900 dark:text-white">{{ $snapshot['energy'] }}%</span>
                        <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Énergie</span>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-[1.5rem] border border-white/50 dark:border-white/5 text-center shadow-lg shadow-indigo-900/5">
                        <span class="block text-3xl mb-2 drop-shadow-sm">😴</span>
                        <span class="block text-lg font-black text-gray-900 dark:text-white truncate mt-1 leading-tight">{{ $snapshot['sleep'] === 'Somewhat' ? 'Moyen' : ($snapshot['sleep'] === 'Yes' ? 'Bon' : 'Mauvais') }}</span>
                        <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Sommeil</span>
                    </div>
                </div>

                <!-- Advice Box -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 p-6 rounded-[1.5rem] border border-indigo-100 dark:border-indigo-800 mb-8 shadow-inner">
                    <p class="text-base font-bold text-indigo-900 dark:text-indigo-200 text-center leading-relaxed">"{{ $advice }}"</p>
                </div>

                <!-- Recommended Actions -->
                <div class="space-y-4 mt-auto">
                    <h4 class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2 mb-2">Actions recommandées</h4>
                    
                    <button wire:click="startBreathing" class="w-full flex items-center justify-between p-4 bg-white/90 dark:bg-gray-800/90 rounded-[1.5rem] border border-white/50 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 transition-all shadow-md group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-6 transition-transform shadow-sm">🫁</div>
                            <div class="text-left">
                                <p class="text-base font-black text-gray-900 dark:text-white mb-0.5">Exercice de Respiration</p>
                                <p class="text-xs font-medium text-gray-500">Prends 2 minutes pour te recentrer</p>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <a href="{{ route('destiny.lobby') }}" class="flex items-center justify-between p-4 bg-white/90 dark:bg-gray-800/90 rounded-[1.5rem] border border-white/50 dark:border-gray-700 hover:border-purple-400 dark:hover:border-purple-500 transition-all shadow-md group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-6 transition-transform shadow-sm">💬</div>
                            <div>
                                <p class="text-base font-black text-gray-900 dark:text-white mb-0.5">Parler à quelqu'un</p>
                                <p class="text-xs font-medium text-gray-500">Connexion anonyme bienveillante</p>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-purple-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('teletherapy.directory') }}" class="flex items-center justify-between p-4 bg-white/90 dark:bg-gray-800/90 rounded-[1.5rem] border border-white/50 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 transition-all shadow-md group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-6 transition-transform shadow-sm">🩺</div>
                            <div>
                                <p class="text-base font-black text-gray-900 dark:text-white mb-0.5">Voir un Professionnel</p>
                                <p class="text-xs font-medium text-gray-500">Réserver une consultation</p>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="mt-8 flex justify-center">
                    <button wire:click="resetCheck" class="text-xs font-bold text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 uppercase tracking-widest transition-colors flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Refaire le check-in
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Breathing Exercise Overlay --}}
    @if($showBreathingExercise)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/80 backdrop-blur-xl transition-all duration-500" x-data="breathingExercise()" x-init="start()">
        <!-- Dynamic ambient glow behind the modal -->
        <div class="absolute w-96 h-96 bg-emerald-500/20 blur-[100px] rounded-full animate-pulse" style="animation-duration: 4s;"></div>
        
        <div class="relative w-full max-w-lg mx-4">
            <button wire:click="closeBreathing" class="absolute -top-12 right-0 text-white/50 hover:text-white text-3xl z-10 transition-colors">&times;</button>
            <div class="bg-white/10 dark:bg-gray-900/40 backdrop-blur-2xl border border-white/20 dark:border-white/10 rounded-[3rem] p-12 text-center shadow-2xl">
                
                <p class="text-emerald-400 text-sm font-black uppercase tracking-widest mb-10 drop-shadow-md" x-text="phaseLabel">Inspirez...</p>
                
                <div class="relative w-48 h-48 mx-auto mb-12">
                    <div class="absolute inset-0 rounded-full bg-emerald-500/20 animate-ping" style="animation-duration: 4s;"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="rounded-full bg-gradient-to-tr from-emerald-400 to-teal-400 transition-all duration-[4000ms] ease-in-out shadow-lg shadow-emerald-500/50 flex items-center justify-center"
                             :style="`width: ${circleSize}px; height: ${circleSize}px;`">
                             <span class="text-white font-black text-5xl mix-blend-overlay opacity-80" x-text="timer"></span>
                        </div>
                    </div>
                </div>
                
                <p class="text-emerald-100/70 text-sm font-bold uppercase tracking-widest">Cycle <span x-text="cycle" class="text-emerald-300">1</span> / 4</p>
                
                <div class="mt-6 flex justify-center gap-2">
                    <template x-for="i in 4">
                        <div class="w-3 h-3 rounded-full transition-all duration-500 shadow-inner"
                             :class="i <= cycle ? 'bg-emerald-400 scale-110 shadow-emerald-500/50' : 'bg-emerald-900/50'"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <script>
    function breathingExercise() {
        return {
            phase: 'inhale',
            timer: 4,
            cycle: 1,
            circleSize: 80,
            phaseLabel: 'Inspirez...',
            _interval: null,
            start() {
                this.runPhase();
            },
            runPhase() {
                const phases = [
                    { name: 'inhale', label: 'Inspirez profondément...', duration: 4, size: 190 },
                    { name: 'hold', label: 'Retenez votre souffle...', duration: 4, size: 190 },
                    { name: 'exhale', label: 'Expirez lentement...', duration: 6, size: 80 },
                    { name: 'rest', label: 'Pause...', duration: 2, size: 80 },
                ];
                let phaseIdx = 0;
                const next = () => {
                    const p = phases[phaseIdx];
                    this.phase = p.name;
                    this.phaseLabel = p.label;
                    this.timer = p.duration;
                    this.circleSize = p.size;
                    if (this._interval) clearInterval(this._interval);
                    this._interval = setInterval(() => {
                        this.timer--;
                        if (this.timer <= 0) {
                            clearInterval(this._interval);
                            phaseIdx++;
                            if (phaseIdx >= phases.length) {
                                phaseIdx = 0;
                                this.cycle++;
                                if (this.cycle > 4) {
                                    this.phaseLabel = 'Terminé ! 🧘';
                                    this.circleSize = 120;
                                    return;
                                }
                            }
                            next();
                        }
                    }, 1000);
                };
                next();
            }
        };
    }
    </script>
    @endif
</div>
