<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Title & Subtitle -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight sm:text-4xl bg-gradient-to-r from-purple-600 via-pink-500 to-indigo-600 bg-clip-text text-transparent">
                Espace Bien-être AF IYI
            </h1>
            <p class="mt-3 text-base text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                Prenez du temps pour vous. Pratiquez des exercices de respiration, méditez avec des ambiances relaxantes, étirez-vous ou écrivez vos pensées du jour.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-64 shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-150 dark:border-gray-700 space-y-2">
                    @php
                        $tabs = [
                            'breathing' => ['🧘', 'Respiration'],
                            'meditation' => ['☮️', 'Méditation'],
                            'stretching' => ['🤸', 'Étirements'],
                            'journal' => ['📝', 'Journal Intime'],
                        ];
                    @endphp
                    @foreach($tabs as $tab => $info)
                        <button wire:click="selectTab('{{ $tab }}')"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-black transition
                            {{ $currentTab === $tab 
                                ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg' 
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-purple-600 dark:hover:text-purple-400' }}">
                            <span class="text-base">{{ $info[0] }}</span>
                            <span>{{ $info[1] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-150 dark:border-gray-700 min-h-[500px] flex flex-col justify-between">
                    
                    {{-- ── TAB 1: BREATHING ────────────────────────────────────────── --}}
                    @if($currentTab === 'breathing')
                        <div class="flex flex-col items-center justify-between h-full flex-1"
                             x-data="{
                                 mode: @entangle('breathingMode'),
                                 step: 'ready', {{-- ready, inhale, hold, exhale, holdBox --}}
                                 secondsLeft: 0,
                                 timer: null,
                                 cycleCount: 0,
                                 statusText: 'Prêt à commencer',

                                 startExercise() {
                                     this.cycleCount = 1;
                                     this.inhalePhase();
                                 },
                                 stopExercise() {
                                     clearInterval(this.timer);
                                     this.step = 'ready';
                                     this.statusText = 'Prêt à commencer';
                                 },
                                 inhalePhase() {
                                     this.step = 'inhale';
                                     this.secondsLeft = 4;
                                     this.statusText = 'Inspirez lentement par le nez...';
                                     this.runTimer(() => {
                                         if (this.mode === '478') {
                                             this.holdPhase();
                                         } else {
                                             this.holdBoxPhase1();
                                         }
                                     });
                                 },
                                 holdPhase() {
                                     this.step = 'hold';
                                     this.secondsLeft = 7;
                                     this.statusText = 'Retenez votre souffle...';
                                     this.runTimer(() => this.exhalePhase());
                                 },
                                 holdBoxPhase1() {
                                     this.step = 'hold';
                                     this.secondsLeft = 4;
                                     this.statusText = 'Retenez votre souffle...';
                                     this.runTimer(() => this.exhalePhase());
                                 },
                                 exhalePhase() {
                                     this.step = 'exhale';
                                     this.secondsLeft = (this.mode === '478') ? 8 : 4;
                                     this.statusText = 'Expirez lentement par la bouche...';
                                     this.runTimer(() => {
                                         if (this.mode === '478') {
                                             this.cycleCount++;
                                             this.inhalePhase();
                                         } else {
                                             this.holdBoxPhase2();
                                         }
                                     });
                                 },
                                 holdBoxPhase2() {
                                     this.step = 'holdBox';
                                     this.secondsLeft = 4;
                                     this.statusText = 'Poumons vides, attendez...';
                                     this.runTimer(() => {
                                         this.cycleCount++;
                                         this.inhalePhase();
                                     });
                                 },
                                 runTimer(callback) {
                                     clearInterval(this.timer);
                                     this.timer = setInterval(() => {
                                         this.secondsLeft--;
                                         if (this.secondsLeft <= 0) {
                                             clearInterval(this.timer);
                                             callback();
                                         }
                                     }, 1000);
                                 }
                             }"
                             @destroy="stopExercise()">
                            
                            <!-- Header -->
                            <div class="text-center w-full">
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Guide de Respiration Thérapeutique</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-lg mx-auto">
                                    Une respiration contrôlée permet d'abaisser le rythme cardiaque, réduire le cortisol et calmer instantanément l'esprit.
                                </p>

                                <!-- Mode selection -->
                                <div class="flex gap-2 justify-center mt-6">
                                    <button @click="mode = '478'; stopExercise();"
                                        class="px-4 py-2 text-xs font-bold rounded-xl border-2 transition
                                        :class="mode === '478' ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/20 text-purple-600' : 'border-gray-250 dark:border-gray-700 text-gray-500'">
                                        4-7-8 (Sommeil & Calme)
                                    </button>
                                    <button @click="mode = 'box'; stopExercise();"
                                        class="px-4 py-2 text-xs font-bold rounded-xl border-2 transition
                                        :class="mode === 'box' ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/20 text-purple-600' : 'border-gray-250 dark:border-gray-700 text-gray-500'">
                                        Box Breathing (Focus & Énergie)
                                    </button>
                                </div>
                            </div>

                            <!-- Animated Circle visualizer -->
                            <div class="relative w-72 h-72 flex items-center justify-center my-10">
                                <!-- Outer Pulse Aura -->
                                <div class="absolute inset-0 rounded-full transition-all duration-1000 bg-purple-500/10"
                                     :class="{
                                         'scale-100': step === 'ready',
                                         'scale-150 bg-purple-500/20 duration-[4000ms]': step === 'inhale',
                                         'scale-150 bg-pink-500/20 duration-[7000ms]': step === 'hold' && mode === '478',
                                         'scale-150 bg-pink-500/20 duration-[4000ms]': step === 'hold' && mode === 'box',
                                         'scale-90 bg-indigo-500/10 duration-[8000ms]': step === 'exhale' && mode === '478',
                                         'scale-90 bg-indigo-500/10 duration-[4000ms]': step === 'exhale' && mode === 'box',
                                         'scale-75 bg-blue-500/5 duration-[4000ms]': step === 'holdBox'
                                     }"></div>

                                <!-- Inner Circle -->
                                <div class="w-48 h-48 rounded-full border border-purple-200 dark:border-purple-800 flex flex-col items-center justify-center text-center transition-all duration-1000 shadow-xl"
                                     :class="{
                                         'bg-white dark:bg-gray-855 scale-100': step === 'ready',
                                         'bg-purple-600 text-white scale-125 duration-[4000ms]': step === 'inhale',
                                         'bg-pink-600 text-white scale-125 duration-[7000ms]': step === 'hold' && mode === '478',
                                         'bg-pink-600 text-white scale-125 duration-[4000ms]': step === 'hold' && mode === 'box',
                                         'bg-indigo-600 text-white scale-95 duration-[8000ms]': step === 'exhale' && mode === '478',
                                         'bg-indigo-600 text-white scale-95 duration-[4000ms]': step === 'exhale' && mode === 'box',
                                         'bg-blue-600 text-white scale-80 duration-[4000ms]': step === 'holdBox'
                                     }">
                                    
                                    <span class="text-xs uppercase font-bold tracking-wider opacity-80" x-text="step === 'ready' ? 'Cycle 0' : 'Cycle ' + cycleCount"></span>
                                    <span class="text-3xl font-black mt-2" x-text="step === 'ready' ? '🧘' : secondsLeft + 's'"></span>
                                    <span class="text-xs font-semibold mt-2 px-3" x-text="step === 'inhale' ? 'Inspirez' : (step === 'hold' ? 'Bloquez' : (step === 'exhale' ? 'Expirez' : (step === 'holdBox' ? 'Attendez' : 'Commencer')))"></span>
                                </div>
                            </div>

                            <!-- Footer controls -->
                            <div class="text-center w-full space-y-4">
                                <p class="text-sm font-black text-purple-700 dark:text-purple-400 min-h-[1.5rem]" x-text="statusText"></p>
                                
                                <div class="flex justify-center gap-2">
                                    <template x-if="step === 'ready'">
                                        <button @click="startExercise()" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-black rounded-2xl shadow transition">
                                            Démarrer l'exercice
                                        </button>
                                    </template>
                                    <template x-if="step !== 'ready'">
                                        <button @click="stopExercise()" class="px-8 py-3 bg-red-650 hover:bg-red-700 text-white text-sm font-black rounded-2xl shadow transition">
                                            Arrêter l'exercice
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                    {{-- ── TAB 2: MEDITATION ───────────────────────────────────────── --}}
                    @elseif($currentTab === 'meditation')
                        <div class="flex flex-col items-center justify-between h-full flex-1"
                             x-data="{
                                 duration: @entangle('meditationDuration'),
                                 sound: @entangle('meditationSound'),
                                 isMeditating: @entangle('isMeditating'),
                                 secondsLeft: 0,
                                 timer: null,
                                 quote: 'Préparez votre espace, fermez les yeux et respirez.',
                                 quotes: [
                                     'Le calme réside en vous, peu importe le tumulte extérieur.',
                                     'Laissez vos pensées passer comme des nuages dans le ciel.',
                                     'Le moment présent est le seul endroit où la vie existe.',
                                     'Inspirez la paix, expirez les soucis.',
                                     'Votre esprit est un jardin. Prenez soin de vos pensées.',
                                     'Tout ce dont vous avez besoin est déjà en vous.'
                                 ],
                                 
                                 startMeditation() {
                                     this.isMeditating = true;
                                     this.secondsLeft = this.duration * 60;
                                     this.quote = this.quotes[Math.floor(Math.random() * this.quotes.length)];
                                     
                                     this.timer = setInterval(() => {
                                         this.secondsLeft--;
                                         
                                         // Rotate quotes every 45 seconds
                                         if (this.secondsLeft % 45 === 0) {
                                             this.quote = this.quotes[Math.floor(Math.random() * this.quotes.length)];
                                         }
                                         
                                         if (this.secondsLeft <= 0) {
                                             this.stopMeditation();
                                         }
                                     }, 1000);
                                 },
                                 stopMeditation() {
                                     clearInterval(this.timer);
                                     this.isMeditating = false;
                                     this.quote = 'Séance complétée. Namaste.';
                                 },
                                 formatTime(secs) {
                                     const m = Math.floor(secs / 60);
                                     const s = secs % 60;
                                     return `${m}:${s < 10 ? '0' : ''}${s}`;
                                 }
                             }"
                             @destroy="clearInterval(timer)">
                            
                            <div class="text-center w-full">
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Studio de Méditation Pleine Conscience</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-lg mx-auto">
                                    Accordez-vous une pause mentale. Définissez un temps, choisissez un son d'ambiance et relaxez-vous.
                                </p>
                            </div>

                            <div class="flex flex-col items-center my-8 w-full max-w-md">
                                <template x-if="!isMeditating">
                                    <div class="space-y-6 w-full bg-gray-50 dark:bg-gray-900/40 p-6 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <!-- Duration -->
                                        <div>
                                            <label class="block text-xs font-black uppercase text-gray-500 mb-2">Durée de la séance</label>
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach([2, 5, 10, 15] as $min)
                                                    <button @click="duration = {{ $min }}"
                                                        class="py-2.5 rounded-xl border-2 font-bold text-xs transition
                                                        :class="duration === {{ $min }} ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/20 text-purple-600' : 'border-gray-250 dark:border-gray-700 text-gray-500 bg-white dark:bg-gray-800'">
                                                        {{ $min }} min
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Ambient Sound -->
                                        <div>
                                            <label class="block text-xs font-black uppercase text-gray-500 mb-2">Ambiance Sonore</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                @php
                                                    $sounds = [
                                                        'silence' => '🧘 Silence Zen',
                                                        'rain' => '🌧️ Pluie Apaisante',
                                                        'ocean' => '🌊 Vagues de l\'Océan',
                                                        'forest' => '🌳 Vent de la Forêt',
                                                    ];
                                                @endphp
                                                @foreach($sounds as $key => $label)
                                                    <button @click="sound = '{{ $key }}'"
                                                        class="py-2.5 px-3 rounded-xl border-2 font-bold text-xs text-left transition
                                                        :class="sound === '{{ $key }}' ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/20 text-purple-600' : 'border-gray-250 dark:border-gray-700 text-gray-500 bg-white dark:bg-gray-800'">
                                                        {{ $label }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="isMeditating">
                                    <div class="text-center space-y-6">
                                        <!-- Ambient Animations -->
                                        <div class="flex justify-center items-center gap-1.5 h-16">
                                            @foreach(range(1, 8) as $bar)
                                                <div class="w-1.5 bg-gradient-to-t from-purple-500 to-pink-500 rounded-full animate-bounce"
                                                     style="animation-delay: {{ $bar * 0.15 }}s; height: 10px; animation-duration: 1.5s;"></div>
                                            @endforeach
                                        </div>

                                        <div class="text-4xl font-black text-gray-900 dark:text-white" x-text="formatTime(secondsLeft)"></div>

                                        <p class="text-sm italic text-gray-600 dark:text-gray-300 max-w-xs mx-auto min-h-[4rem]" x-text="quote"></p>
                                    </div>
                                </template>
                            </div>

                            <!-- Controls -->
                            <div class="text-center w-full">
                                <template x-if="!isMeditating">
                                    <button @click="startMeditation()" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-black rounded-2xl shadow transition">
                                        Lancer la méditation
                                    </button>
                                </template>
                                <template x-if="isMeditating">
                                    <button @click="stopMeditation()" class="px-8 py-3 bg-red-650 hover:bg-red-700 text-white text-sm font-black rounded-2xl shadow transition">
                                        Terminer la séance
                                    </button>
                                </template>
                            </div>
                        </div>

                    {{-- ── TAB 3: STRETCHING ───────────────────────────────────────── --}}
                    @elseif($currentTab === 'stretching')
                        <div class="flex flex-col items-center justify-between h-full flex-1"
                             x-data="{
                                 index: @entangle('currentStretchIndex'),
                                 completed: @entangle('stretchCompleted'),
                                 secondsLeft: 30,
                                 timer: null,
                                 isRunning: false,
                                 stretches: [
                                     {
                                         title: '🧘 1. Étirement du cou',
                                         instructions: 'Penchez doucement votre oreille droite vers votre épaule droite. Maintenez la position et respirez profondément. Répétez ensuite du côté gauche.',
                                         target: 'Cou & Trapèzes',
                                         icon: '🦒'
                                     },
                                     {
                                         title: '🔄 2. Roulade des épaules',
                                         instructions: 'Faites de grands cercles lents avec vos épaules vers l\'arrière, puis vers l\'avant. Idéal pour relâcher les tensions accumulées devant les écrans.',
                                         target: 'Épaules & Haut du dos',
                                         icon: '🔄'
                                     },
                                     {
                                         title: '🪑 3. Torsion assise',
                                         instructions: 'Asseyez-vous bien droit. Placez votre main gauche sur votre genou droit extérieur, puis tournez doucement votre torse vers la droite en regardant derrière vous.',
                                         target: 'Colonne vertébrale & Lombo',
                                         icon: '🪑'
                                     }
                                 ],

                                 startTimer() {
                                     this.isRunning = true;
                                     clearInterval(this.timer);
                                     this.timer = setInterval(() => {
                                         this.secondsLeft--;
                                         if (this.secondsLeft <= 0) {
                                             clearInterval(this.timer);
                                             this.isRunning = false;
                                         }
                                     }, 1000);
                                 },
                                 resetTimer() {
                                     clearInterval(this.timer);
                                     this.secondsLeft = 30;
                                     this.isRunning = false;
                                 },
                                 nextStretch() {
                                     this.resetTimer();
                                     if (this.index < this.stretches.length - 1) {
                                         this.index++;
                                     } else {
                                         this.completed = true;
                                     }
                                 },
                                 restart() {
                                     this.index = 0;
                                     this.completed = false;
                                     this.resetTimer();
                                 }
                             }"
                             @destroy="clearInterval(timer)">
                            
                            <div class="text-center w-full">
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Pause Étirements Physiques</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-lg mx-auto">
                                    Une routine d'étirements rapides conçue pour libérer les tensions musculaires accumulées pendant la journée.
                                </p>
                            </div>

                            <div class="my-8 w-full max-w-md">
                                <template x-if="!completed">
                                    <div class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 space-y-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-3xl" x-text="stretches[index].icon"></div>
                                            <div>
                                                <h3 class="font-extrabold text-sm text-gray-800 dark:text-white" x-text="stretches[index].title"></h3>
                                                <span class="inline-block mt-1 bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 text-[10px] font-black px-2 py-0.5 rounded-full" x-text="stretches[index].target"></span>
                                            </div>
                                        </div>

                                        <p class="text-xs text-gray-650 dark:text-gray-300 leading-relaxed min-h-[3rem]" x-text="stretches[index].instructions"></p>

                                        <!-- Circular stretch progress/timer -->
                                        <div class="flex flex-col items-center justify-center pt-2">
                                            <div class="text-2xl font-black text-gray-900 dark:text-white" x-text="secondsLeft + 's'"></div>
                                            <p class="text-[10px] text-gray-400 mt-1" x-text="isRunning ? 'Maintenez la pose...' : 'Prêt à commencer'"></p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="completed">
                                    <div class="text-center p-8 bg-purple-50/20 dark:bg-purple-950/5 rounded-3xl border border-purple-100 dark:border-purple-900/50 space-y-4">
                                        <div class="text-4xl">🏆</div>
                                        <h3 class="font-black text-gray-900 dark:text-white">Routine complétée !</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Bravo ! Vous avez pris 3 minutes pour détendre vos muscles. Votre corps vous remercie.</p>
                                    </div>
                                </template>
                            </div>

                            <div class="text-center w-full">
                                <template x-if="!completed">
                                    <div class="flex justify-center gap-2">
                                        <template x-if="!isRunning">
                                            <button @click="startTimer()" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-black rounded-xl shadow transition">
                                                Lancer la pose (30s)
                                            </button>
                                        </template>
                                        <button @click="nextStretch()" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-650 text-gray-750 dark:text-gray-200 text-xs font-black rounded-xl transition">
                                            Passer / Suivant
                                        </button>
                                    </div>
                                </template>
                                <template x-if="completed">
                                    <button @click="restart()" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-black rounded-2xl shadow transition">
                                        Refaire les étirements
                                    </button>
                                </template>
                            </div>
                        </div>

                    {{-- ── TAB 4: GRATITUDE JOURNAL ────────────────────────────────── --}}
                    @elseif($currentTab === 'journal')
                        <div class="flex flex-col justify-between h-full flex-1">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Journal Intime & Gratitude</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Écrire quotidiennement aide à structurer ses pensées, cultiver la gratitude et réduire l'anxiété chronique.
                                </p>

                                @if (session()->has('journal_success'))
                                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 rounded-2xl text-xs font-bold">
                                        {{ session('journal_success') }}
                                    </div>
                                @endif

                                <!-- Form -->
                                <form wire:submit.prevent="saveJournalEntry" class="mt-6 space-y-4">
                                    <div class="p-4 bg-purple-50/40 dark:bg-purple-950/10 rounded-2xl border border-purple-100 dark:border-purple-900/30">
                                        <span class="text-[10px] font-black uppercase text-purple-600 dark:text-purple-400 tracking-wider">Question de réflexion</span>
                                        <p class="text-xs font-bold text-gray-750 dark:text-gray-200 mt-1 italic">" {{ $journalPrompt }} "</p>
                                    </div>

                                    <div>
                                        <textarea wire:model="journalContent" rows="4" placeholder="Écrivez vos pensées ici..."
                                            class="w-full text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-700 rounded-2xl p-4 focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:text-white placeholder-gray-400" required></textarea>
                                        @error('journalContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-black rounded-xl shadow transition">
                                            Enregistrer ma réflexion
                                        </button>
                                    </div>
                                </form>

                                <!-- Entries List -->
                                <div class="mt-8 space-y-4 border-t border-gray-150 dark:border-gray-700 pt-8">
                                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-500">Mes Réflexions Passées</h3>
                                    
                                    @forelse($journalEntries as $entry)
                                        <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 relative group shadow-sm">
                                            <button wire:click="deleteJournalEntry('{{ $entry['id'] }}')" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xs transition" title="Supprimer">
                                                ✕
                                            </button>
                                            <span class="text-[9px] text-gray-400 font-bold block">{{ $entry['date'] }}</span>
                                            <p class="text-[10px] text-purple-600 dark:text-purple-400 font-bold mt-1.5 italic">Q : {{ $entry['prompt'] }}</p>
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-2 whitespace-pre-wrap leading-relaxed">{{ $entry['content'] }}</p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 text-center py-6">Aucune réflexion enregistrée pour le moment. Commencez à écrire ci-dessus !</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
