<div x-data @toggle-ai-chat.window="$wire.toggleChat()">
    <!-- Chat Window -->
    @if($isOpen)
    <div class="fixed bottom-24 right-6 w-[400px] bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden z-50 border border-gray-150 dark:border-gray-700 flex flex-col transition-all duration-300 animate-fade-in" style="height: 550px;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 p-4 flex justify-between items-center text-white shadow-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center animate-pulse">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-black text-sm tracking-wide">Assistant Wellness</h3>
                        <span class="bg-white/25 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full uppercase tracking-wider">{{ $this->sentiment }}</span>
                    </div>
                    <p class="text-[10px] text-purple-100">Toujours disponible pour vous écouter</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Clear History Button -->
                <button wire:click="clearChat" class="text-white/80 hover:text-white p-1 hover:bg-white/10 rounded-lg transition" title="Effacer l'historique">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                
                <!-- Close Button -->
                <button wire:click="toggleChat" class="text-white/80 hover:text-white p-1 hover:bg-white/10 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        @if($activeMode === 'chat')
            <!-- Messages Area -->
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50 dark:bg-gray-900 space-y-4" id="chat-messages">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        @if($msg['role'] !== 'user')
                            <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-xs text-purple-600 dark:text-purple-400 mr-2 flex-shrink-0 font-bold">
                                🤖
                            </div>
                        @endif
                        <div class="max-w-[75%] rounded-2xl px-4 py-2.5 text-xs shadow-sm leading-relaxed
                            {{ $msg['role'] === 'user' 
                                ? 'bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-tr-none' 
                                : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-150 dark:border-gray-700 rounded-tl-none' }}">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Suggestions & Actions -->
            <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border-t border-gray-150 dark:border-gray-750">
                <div class="flex flex-wrap gap-1.5 items-center">
                    <button wire:click="startBreathing" class="px-2.5 py-1.5 bg-pink-500 hover:bg-pink-600 text-white text-[10px] font-bold rounded-lg transition flex items-center gap-1 shadow-sm">
                        🧘 Respiration guidée (4-7-8)
                    </button>
                    @php
                        $suggestions = [
                            'Gérer mon stress' => '😰 Gérer le stress',
                            'Conseils pour dormir' => '😴 Conseils sommeil',
                            'Besoin de me motiver' => '❤️ Me motiver',
                        ];
                    @endphp
                    @foreach($suggestions as $prompt => $label)
                        <button wire:click="triggerSuggestion('{{ $prompt }}')" class="px-2.5 py-1.5 bg-white dark:bg-gray-850 border border-gray-250 dark:border-gray-700 hover:border-purple-500 hover:text-purple-600 dark:hover:text-purple-400 text-gray-600 dark:text-gray-300 text-[10px] font-semibold rounded-lg transition shadow-sm">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-150 dark:border-gray-700">
                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                    <input wire:model="message" type="text" placeholder="Discutez avec votre coach bien-être..." class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-650 text-gray-800 dark:text-gray-200 rounded-xl px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent placeholder-gray-400">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-2 w-9 h-9 flex items-center justify-center transition-colors shadow-md">
                        <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>

        @elseif($activeMode === 'breathing')
            <!-- Breathing Exercise Screen -->
            <div class="flex-1 bg-gradient-to-b from-gray-50 to-purple-50/20 dark:from-gray-900 dark:to-purple-950/10 p-6 flex flex-col items-center justify-between"
                 x-data="{
                     step: 'ready',
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
                         this.statusText = 'Inspirez par le nez...';
                         this.runTimer(() => this.holdPhase());
                     },
                     holdPhase() {
                         this.step = 'hold';
                         this.secondsLeft = 7;
                         this.statusText = 'Retenez votre respiration...';
                         this.runTimer(() => this.exhalePhase());
                     },
                     exhalePhase() {
                         this.step = 'exhale';
                         this.secondsLeft = 8;
                         this.statusText = 'Expirez lentement par la bouche...';
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
                 x-init="$watch('isOpen', val => { if(!val) stopExercise(); })"
                 @destroy="stopExercise()">
                
                <div class="text-center">
                     <h4 class="text-sm font-black text-gray-800 dark:text-gray-200">Exercice de Respiration 4-7-8</h4>
                     <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Calme le système nerveux et réduit l'anxiété instantanément.</p>
                </div>

                <!-- Animated Circle -->
                <div class="relative w-48 h-48 flex items-center justify-center">
                    <!-- Dynamic Glowing Rings -->
                    <div class="absolute inset-0 rounded-full transition-all duration-1000 bg-purple-500/10"
                         :class="{
                             'scale-100': step === 'ready',
                             'scale-150 bg-purple-500/20 duration-[4000ms]': step === 'inhale',
                             'scale-150 bg-pink-500/20 duration-[7000ms]': step === 'hold',
                             'scale-90 bg-indigo-500/10 duration-[8000ms]': step === 'exhale'
                         }"></div>

                    <div class="w-36 h-36 rounded-full border border-purple-200 dark:border-purple-800 flex flex-col items-center justify-center text-center transition-all duration-1000 shadow-xl"
                         :class="{
                             'bg-white dark:bg-gray-850 scale-100': step === 'ready',
                             'bg-purple-600 text-white scale-125 duration-[4000ms]': step === 'inhale',
                             'bg-pink-600 text-white scale-125 duration-[7000ms]': step === 'hold',
                             'bg-indigo-600 text-white scale-90 duration-[8000ms]': step === 'exhale'
                         }">
                        
                        <span class="text-[10px] uppercase font-bold tracking-wider opacity-85" x-text="step === 'ready' ? 'Cycle 0' : 'Cycle ' + cycleCount"></span>
                        <span class="text-xl font-black mt-1" x-text="step === 'ready' ? '🧘' : secondsLeft + 's'"></span>
                        <span class="text-[10px] font-semibold mt-1 px-2" x-text="step === 'inhale' ? 'Inspirez' : (step === 'hold' ? 'Bloquez' : (step === 'exhale' ? 'Expirez' : 'Commencer'))"></span>
                    </div>
                </div>

                <div class="text-center space-y-4 w-full">
                    <p class="text-xs font-bold text-purple-700 dark:text-purple-400 min-h-[1.5rem]" x-text="statusText"></p>
                    
                    <div class="flex gap-2 justify-center">
                        <template x-if="step === 'ready'">
                            <button @click="startExercise()" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow transition">
                                Démarrer l'exercice
                            </button>
                        </template>
                        <template x-if="step !== 'ready'">
                            <button @click="stopExercise()" class="px-6 py-2 bg-red-650 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow transition">
                                Arrêter
                            </button>
                        </template>
                        <button wire:click="stopBreathing" @click="stopExercise()" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-650 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition">
                            Retour au chat
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Scroll to bottom script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-messages');
            if (container) container.scrollTop = container.scrollHeight;
            
            Livewire.hook('morph.updated', ({ component, el }) => {
                if (component.name === 'patient.ai-chat-widget') {
                    const chatContainer = document.getElementById('chat-messages');
                    if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
        });
    </script>
    @endif
</div>
