<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Centre d\'Activités Bien-être') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        completedLogs: {{ json_encode($completed) }},
        completeActivity(activityId) {
            if (this.completedLogs.includes(activityId)) return;
            fetch('{{ route('teletherapy.activities.complete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ activity_id: activityId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.completedLogs.push(activityId);
                }
            });
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Coherent Breathing / Box Breathing (Core Widget) -->
            <div class="bg-gradient-to-tr from-indigo-900 to-slate-900 rounded-3xl p-8 text-white border border-indigo-500/30 shadow-2xl relative overflow-hidden flex flex-col lg:flex-row items-center gap-12"
                 x-data="{
                     active: false,
                     text: 'Prêt à commencer ?',
                     phase: 'idle', // inhale, hold_in, exhale, hold_out
                     secondsLeft: 4,
                     interval: null,
                     timer: null,
                     cycles: 0,
                     start() {
                         this.active = true;
                         this.cycles = 0;
                         this.runPhase('inhale', 4);
                     },
                     stop() {
                         this.active = false;
                         this.phase = 'idle';
                         this.text = 'Prêt à commencer ?';
                         clearInterval(this.interval);
                         clearTimeout(this.timer);
                     },
                     runPhase(nextPhase, duration) {
                         this.phase = nextPhase;
                         this.secondsLeft = duration;
                         this.text = this.getLabel(nextPhase);

                         clearInterval(this.interval);
                         this.interval = setInterval(() => {
                             this.secondsLeft--;
                             if(this.secondsLeft <= 0) {
                                 clearInterval(this.interval);
                             }
                         }, 1000);

                         this.timer = setTimeout(() => {
                             if (!this.active) return;
                             let next = '';
                             let nextDur = 4;
                             if (nextPhase === 'inhale') { next = 'hold_in'; }
                             else if (nextPhase === 'hold_in') { next = 'exhale'; }
                             else if (nextPhase === 'exhale') { next = 'hold_out'; }
                             else if (nextPhase === 'hold_out') { 
                                 next = 'inhale'; 
                                 this.cycles++;
                                 if (this.cycles >= 3) {
                                     completeActivity('box_breathing');
                                 }
                             }
                             this.runPhase(next, nextDur);
                         }, duration * 1000);
                     },
                     getLabel(phase) {
                         return match = {
                             'inhale': 'Inspirez lentement...',
                             'hold_in': 'Retenez votre souffle...',
                             'exhale': 'Expirez doucement...',
                             'hold_out': 'Retenez vide...',
                             'idle': 'Prêt à commencer ?'
                         }[phase];
                     }
                 }"
                 @destroy="stop()">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNSkiLz48L3N2Zz4=')] opacity-20"></div>
                
                <!-- Left: Breathing circle -->
                <div class="flex-1 flex flex-col items-center justify-center relative z-10">
                    <div class="relative w-56 h-56 flex items-center justify-center">
                        <!-- Pulse Ring -->
                        <div class="absolute inset-0 rounded-full bg-indigo-500/20 transition-all duration-[4000ms] ease-in-out"
                             :class="{
                                 'scale-[1.5] opacity-60': phase === 'inhale',
                                 'scale-[1.0] opacity-20': phase === 'exhale' || phase === 'hold_out' || phase === 'idle',
                                 'scale-[1.5] opacity-40': phase === 'hold_in'
                             }">
                        </div>
                        <!-- Inner bubble -->
                        <div class="w-40 h-40 rounded-full bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex flex-col items-center justify-center shadow-2xl transition-all duration-[4000ms] ease-in-out"
                             :class="{
                                 'scale-[1.3]': phase === 'inhale' || phase === 'hold_in',
                                 'scale-[1.0]': phase === 'exhale' || phase === 'hold_out' || phase === 'idle'
                             }">
                            <span class="text-xs font-black tracking-wider uppercase opacity-80" x-text="active ? phase.replace('_', ' ') : 'Zen'"></span>
                            <span class="text-4xl font-black mt-1" x-text="active ? secondsLeft : '🧘‍♂️'"></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Instructions & Control -->
                <div class="flex-[1.5] relative z-10 text-center lg:text-left space-y-4">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">Exercice Vedette</span>
                    <h3 class="text-3xl font-black">Cohérence Cardiaque 4-4-4-4</h3>
                    <p class="text-indigo-200 text-sm leading-relaxed max-w-xl">La respiration carrée régule le système nerveux autonome, abaisse le rythme cardiaque et réduit instantanément la sensation de stress ou de panique.</p>
                    
                    <div class="py-2">
                        <p class="text-lg font-bold text-slate-100" x-text="text"></p>
                        <p class="text-xs text-indigo-300 mt-1" x-show="active">Cycle actuel : <span x-text="cycles"></span> / 3 complétés pour valider l'activité.</p>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <button @click="active ? stop() : start()" 
                                class="px-8 py-3 bg-white text-indigo-900 hover:bg-slate-100 font-extrabold rounded-full transition shadow-lg"
                                x-text="active ? 'Arrêter' : 'Démarrer la session'"></button>
                        <button x-show="completedLogs.includes('box_breathing')" disabled class="px-6 py-3 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold rounded-full flex items-center gap-2">
                            ✓ Enregistré aujourd'hui
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bento of other Activities -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Pomodoro Focus timer -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between"
                     x-data="{
                         time: 1500, // 25 mins
                         timer: null,
                         active: false,
                         isBreak: false,
                         start() {
                             this.active = true;
                             this.timer = setInterval(() => {
                                 this.time--;
                                 if (this.time <= 0) {
                                     clearInterval(this.timer);
                                     this.active = false;
                                     if (!this.isBreak) {
                                         this.isBreak = true;
                                         this.time = 300; // 5 mins break
                                         completeActivity('pomodoro_focus');
                                     } else {
                                         this.isBreak = false;
                                         this.time = 1500;
                                     }
                                 }
                             }, 1000);
                         },
                         pause() {
                             this.active = false;
                             clearInterval(this.timer);
                         },
                         reset() {
                             this.active = false;
                             this.isBreak = false;
                             clearInterval(this.timer);
                             this.time = 1500;
                         },
                         formatTime() {
                             let m = Math.floor(this.time / 60);
                             let s = this.time % 60;
                             return (m < 10 ? '0'+m : m) + ':' + (s < 10 ? '0'+s : s);
                         }
                     }">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="w-12 h-12 bg-rose-50 dark:bg-rose-950/30 text-rose-500 rounded-2xl flex items-center justify-center text-xl">🍅</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-500" x-text="isBreak ? 'Temps de repos' : 'Concentration'"></span>
                        </div>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white mb-2">Focus Pomodoro</h4>
                        <p class="text-gray-500 dark:text-slate-400 text-xs leading-relaxed mb-6">Travaillez sans distractions pendant 25 minutes, puis accordez-vous 5 minutes pour respirer.</p>
                        
                        <div class="text-center py-6">
                            <span class="text-5xl font-black text-slate-800 dark:text-white tracking-widest font-mono" x-text="formatTime()"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button @click="active ? pause() : start()" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm" x-text="active ? 'Pause' : 'Démarrer'"></button>
                        <button @click="reset()" class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">Réinitialiser</button>
                        <template x-if="completedLogs.includes('pomodoro_focus')">
                            <span class="text-emerald-500 text-xs font-bold">✓ Fait</span>
                        </template>
                    </div>
                </div>

                <!-- Gratitude Journal Widget -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between"
                     x-data="{
                         things: ['', '', ''],
                         submitted: false,
                         submit() {
                             if(this.things[0].trim() === '') return;
                             this.submitted = true;
                             completeActivity('sleep_routine'); // logs activity progress
                         }
                     }">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="w-12 h-12 bg-amber-50 dark:bg-amber-950/30 text-amber-500 rounded-2xl flex items-center justify-center text-xl">✍️</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Expression Positive</span>
                        </div>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white mb-2">Journal de Gratitude</h4>
                        <p class="text-gray-500 dark:text-slate-400 text-xs leading-relaxed mb-4">Écrire trois choses pour lesquelles vous êtes reconnaissant permet de rééduquer le cerveau à repérer le positif.</p>
                        
                        <div class="space-y-2 mb-6" x-show="!submitted">
                            <input type="text" x-model="things[0]" placeholder="1. Une petite victoire aujourd'hui..." class="w-full text-xs p-2.5 border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl">
                            <input type="text" x-model="things[1]" placeholder="2. Une personne bienveillante..." class="w-full text-xs p-2.5 border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl">
                            <input type="text" x-model="things[2]" placeholder="3. Un moment calme..." class="w-full text-xs p-2.5 border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl">
                        </div>
                        
                        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl text-slate-700 dark:text-slate-300 text-xs leading-relaxed mb-6" x-show="submitted">
                            <strong>Merveilleux !</strong> Écrire quotidiennement vos gratitudes renforce l'estime de soi et réduit la fatigue mentale. Votre défi de gratitude est validé.
                        </div>
                    </div>

                    <button @click="submit()" x-show="!submitted" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-sm">Valider mes gratitudes</button>
                    <button disabled x-show="submitted" class="w-full py-2.5 bg-emerald-500 text-white rounded-xl text-xs font-bold cursor-not-allowed">Gratitude validée ✓</button>
                </div>
            </div>

            <!-- Static exercises list (Walking Meditation, PMR, sleep routine details) -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6">Fiches d'Exercices Relaxants</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- PMR -->
                    <div class="p-5 border border-slate-100 dark:border-slate-700/50 rounded-2xl bg-slate-50/30 dark:bg-slate-900/20">
                        <h4 class="text-sm font-black text-slate-800 dark:text-white mb-2">Relaxation Musculaire Progressive</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Contractez fortement les muscles de vos pieds pendant 5 secondes, puis relâchez brusquement en expirant. Remontez ainsi le long du corps (mollets, cuisses, mains, épaules, visage). Cette méthode permet de relâcher les tensions physiques résiduelles créées par l'anxiété.
                        </p>
                    </div>
                    <!-- Walking meditation -->
                    <div class="p-5 border border-slate-100 dark:border-slate-700/50 rounded-2xl bg-slate-50/30 dark:bg-slate-900/20">
                        <h4 class="text-sm font-black text-slate-800 dark:text-white mb-2">Méditation en Marche Consciente</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Marchez lentement (10 pas en ligne droite). Concentrez-vous entièrement sur le déroulement de la plante des pieds sur le sol, le balancement des bras, et le rythme de votre respiration. Dès que vos pensées s'égarent, ramenez doucement votre attention sur le contact du talon avec le sol.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
