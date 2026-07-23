<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">My Wellness Dashboard</h2>
    </div>

    <!-- Top Grid: Mood Tracker and Streak -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @livewire('patient.mood-tracker')
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Mood Streak</h3>
            <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center border-4 border-orange-500 mb-4">
                <span class="text-3xl font-bold text-orange-600">🔥 {{ $streak }}</span>
            </div>
            <p class="text-gray-500 text-center">Days in a row logging your mood. Keep it up!</p>
        </div>
    </div>

    <!-- Recommendations -->
    @if(count($recommendations) > 0)
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Personalized Recommendations</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($recommendations as $rec)
                <a href="{{ $rec['link'] }}" class="block p-4 border border-gray-200 rounded-lg hover:border-bio-500 hover:shadow-md transition">
                    <div class="text-sm font-bold text-bio-600 mb-1 uppercase tracking-wide">{{ $rec['type'] }}</div>
                    <h4 class="text-md font-bold text-gray-800 mb-2">{{ $rec['title'] }}</h4>
                    <p class="text-sm text-gray-600">{{ $rec['description'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Mood Chart -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Your Mood Trends</h3>
        <div id="moodChart" style="height: 300px;"></div>
    </div>

    <!-- Consultations -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Teletherapy Consultations</h3>
            
            <div class="mt-2 md:mt-0 flex items-center bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                <input type="checkbox" wire:model="shareMoods" wire:change="toggleShareMoods" id="shareMoodsToggle" class="rounded text-bio-600 border-gray-300 focus:ring-bio-500 w-4 h-4">
                <label for="shareMoodsToggle" class="ml-2 text-sm text-gray-700 font-medium">Share my Mood History with my Therapists</label>
            </div>
        </div>
        
        <div class="flex space-x-4 mb-6 border-b border-gray-200">
            <button wire:click="switchTab('upcoming')" class="pb-2 px-1 {{ $tab === 'upcoming' ? 'border-b-2 border-bio-600 text-bio-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Upcoming ({{ $upcoming->total() }})
            </button>
            <button wire:click="switchTab('past')" class="pb-2 px-1 {{ $tab === 'past' ? 'border-b-2 border-bio-600 text-bio-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Past ({{ $past->total() }})
            </button>
            <button wire:click="switchTab('messages')" class="pb-2 px-1 flex items-center gap-1 {{ $tab === 'messages' ? 'border-b-2 border-bio-600 text-bio-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Messages
            </button>
        </div>

        @if($tab === 'upcoming')
            @if($upcoming->isEmpty())
                <p class="text-gray-500">You have no upcoming consultations.</p>
            @else
                <div class="space-y-4">
                    @foreach($upcoming as $consultation)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl {{ $consultation->started_at ? 'border-green-300 bg-green-50/40' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                                    {{ substr($consultation->therapistProfile->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $consultation->therapistProfile->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $consultation->scheduled_at->format('d/m/Y à H:i') }} · {{ $consultation->duration_minutes }} min</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-full">{{ ucfirst($consultation->status) }}</span>
                                        @if($consultation->started_at)
                                            <span class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>Thérapeute disponible
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 items-end">
                                <a href="{{ route('teletherapy.room', $consultation->id) }}"
                                   class="flex items-center gap-1.5 px-4 py-2 {{ $consultation->started_at ? 'bg-green-600 hover:bg-green-700 shadow-lg shadow-green-200' : 'bg-violet-600 hover:bg-violet-700' }} text-white text-xs font-black rounded-xl transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    {{ $consultation->started_at ? 'Rejoindre maintenant' : 'Lancer l\'appel' }}
                                </a>
                                <button x-data @click="$dispatch('openPreConsultationChat', { id: {{ $consultation->id }} })" class="relative flex items-center justify-center gap-1.5 px-4 py-2 w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl transition shadow-sm">
                                    <span>💬</span> Chat
                                    <livewire:chat-notification-badge :consultation-id="$consultation->id" :key="'badge-p-'.$consultation->id" />
                                </button>
                                <button wire:click="openCancel({{ $consultation->id }})" class="text-[10px] text-red-500 hover:text-red-700 font-bold mt-1">Annuler</button>
                            </div>
                        </div>
                    @endforeach
                    {{ $upcoming->links() }}
                </div>
            @endif
        @else
            @if($past->isEmpty())
                <p class="text-gray-500">You have no past consultations.</p>
            @else
                <div class="space-y-6">
                    @foreach($past as $consultation)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $consultation->therapistProfile->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $consultation->scheduled_at->format('M d, Y - h:i A') }}</p>
                                </div>
                                <button wire:click="openReview({{ $consultation->id }})" class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg hover:bg-yellow-200">Leave Review</button>
                            </div>

                            @php
                                $sharedNotes = $consultation->notes->filter(fn($n) => $n->visibility === 'shared_with_patient');
                            @endphp

                            @if($sharedNotes->isNotEmpty())
                                <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                    <h5 class="text-sm font-bold text-blue-800 mb-2">Therapist's Notes & Recommendations</h5>
                                    <div class="space-y-3">
                                        @foreach($sharedNotes as $note)
                                            <div class="text-sm text-blue-900 bg-white p-3 rounded shadow-sm">
                                                {{ $note->getContentForPatient() ?? 'Encrypted content unavailable' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    {{ $past->links() }}
                </div>
            @endif
        @elseif($tab === 'messages')
            <div class="flex flex-col md:flex-row gap-6 bg-slate-50 dark:bg-slate-900/10 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800" style="height: 600px;">
                
                {{-- Left Panel: Therapists/Conversations --}}
                <div class="w-full md:w-1/3 border-r border-gray-150 dark:border-gray-800 flex flex-col bg-white dark:bg-gray-900/20">
                    <div class="p-4 border-b border-gray-150 dark:border-gray-800">
                        <h4 class="font-extrabold text-gray-800 dark:text-white text-sm">💬 Thérapeutes</h4>
                        <p class="text-[10px] text-slate-400">Sélectionnez un thérapeute pour voir les messages.</p>
                    </div>
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-100/60 dark:divide-gray-800/40">
                        @forelse($conversations as $conv)
                            @php
                                $isSelected = $conv->id === $selectedConsultationId;
                                $unreadCount = $conv->messages()
                                    ->where('sender_id', '!=', Auth::id())
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            <button wire:click="selectConsultation({{ $conv->id }})" class="w-full text-left p-4 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition flex items-center justify-between {{ $isSelected ? 'bg-bio-50/50 dark:bg-bio-950/20 border-l-4 border-bio-500' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-bio-100 dark:bg-bio-900 flex items-center justify-center text-bio-600 dark:text-bio-400 font-extrabold text-xs">
                                        {{ strtoupper(substr($conv->therapistProfile->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $conv->therapistProfile->user->name ?? '—' }}</p>
                                        <p class="text-[9px] text-slate-400 font-mono">Prévue le: {{ $conv->scheduled_at ? $conv->scheduled_at->format('d/m/Y') : '-' }}</p>
                                    </div>
                                </div>
                                
                                @if($unreadCount > 0)
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full bg-bio-600 text-[9px] font-black text-white">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-400">
                                Aucune conversation disponible.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Right Panel: Chat Messages --}}
                <div class="flex-1 flex flex-col bg-white dark:bg-gray-950/10">
                    @if($selectedConsultationId)
                        @php
                            $activeConv = $conversations->firstWhere('id', $selectedConsultationId);
                        @endphp
                        <div class="px-6 py-4 border-b border-gray-150 dark:border-gray-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/15">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Chat avec {{ $activeConv->therapistProfile->user->name ?? 'Thérapeute' }}</h4>
                                <p class="text-[10px] text-slate-400">Consultation {{ $activeConv->status }}</p>
                            </div>
                        </div>

                        {{-- Messages Container --}}
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-900/5" id="patient-inline-chat-messages" wire:poll.5s>
                            @forelse($chatMessages as $msg)
                                @php
                                    $isMe = $msg->sender_id === Auth::id();
                                @endphp
                                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%] {{ $isMe ? 'bg-bio-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-r-2xl rounded-tl-2xl border border-gray-200/40 dark:border-gray-700/40' }} p-3 shadow-sm">
                                        <p class="text-xs leading-relaxed">{{ $msg->message }}</p>
                                        <span class="text-[9px] opacity-60 block mt-1 {{ $isMe ? 'text-right text-bio-200' : 'text-left text-slate-400' }}">
                                            {{ $msg->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2 py-20">
                                    <span class="text-3xl">👋</span>
                                    <p class="text-[11px] font-medium text-center">Aucun message pour le moment.<br>Écrivez ci-dessous pour démarrer l'échange avec votre thérapeute.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Message Input --}}
                        <div class="p-4 border-t border-gray-150 dark:border-gray-800 bg-white dark:bg-gray-900/35">
                            <form wire:submit.prevent="sendMessageText" class="flex gap-2">
                                <input type="text" wire:model.defer="newMessageText" placeholder="Écrivez votre message..." class="flex-1 bg-slate-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-xs rounded-xl px-4 py-3 focus:ring-bio-500 focus:border-bio-500 dark:text-white dark:placeholder-gray-400" required>
                                <button type="submit" class="bg-bio-600 hover:bg-bio-700 text-white rounded-xl px-5 py-3 shadow-sm font-bold text-xs flex items-center justify-center transition" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendMessageText">Envoyer</span>
                                    <span wire:loading wire:target="sendMessageText">...</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-slate-400 space-y-2 py-20">
                            <span class="text-4xl">💬</span>
                            <p class="text-xs font-bold">Sélectionnez un thérapeute sur la gauche pour afficher les messages.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Inline Chat auto-scroll script -->
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const scrollInlineChat = () => {
                        const container = document.getElementById('patient-inline-chat-messages');
                        if (container) container.scrollTop = container.scrollHeight;
                    };
                    
                    // Scroll on load or tab switch
                    setTimeout(scrollInlineChat, 200);
                    
                    // Scroll on morph update
                    Livewire.hook('morph.updated', ({ component }) => {
                        if (component.name === 'patient.consultation-dashboard') {
                            scrollInlineChat();
                        }
                    });
                });
            </script>
        @endif
    </div>

    <!-- Modals -->
    @if($cancellingId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl w-full max-w-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Cancel Consultation</h3>
                <textarea wire:model="cancelReason" class="w-full border-gray-300 rounded-lg mb-4" rows="3" placeholder="Reason for cancellation..."></textarea>
                @error('cancelReason') <span class="text-red-500 text-sm block mb-4">{{ $message }}</span> @enderror
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('cancellingId', null)" class="px-4 py-2 text-gray-600 hover:text-gray-800">Close</button>
                    <button wire:click="confirmCancel" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirm Cancel</button>
                </div>
            </div>
        </div>
    @endif

    @if($reviewingId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl w-full max-w-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Review Therapist</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                    <input type="number" wire:model="rating" min="1" max="5" class="w-full border-gray-300 rounded-lg">
                    @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment (Optional)</label>
                    <textarea wire:model="reviewComment" class="w-full border-gray-300 rounded-lg" rows="3"></textarea>
                    @error('reviewComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" wire:model="reviewAnonymous" id="reviewAnonymous" class="rounded text-bio-600 border-gray-300">
                    <label for="reviewAnonymous" class="ml-2 text-sm text-gray-700">Keep anonymous</label>
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('reviewingId', null)" class="px-4 py-2 text-gray-600 hover:text-gray-800">Close</button>
                    <button wire:click="submitReview" class="px-4 py-2 bg-bio-600 text-white rounded-lg hover:bg-bio-700">Submit Review</button>
                </div>
            </div>
        </div>
    @endif


    <!-- PreConsultation Chat Modal -->
    <livewire:pre-consultation-chat />

    <!-- ApexCharts Script for Mood Chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const moodData = @json($recentMoods->pluck('mood_score'));
            const moodDates = @json($recentMoods->map(fn($m) => $m->created_at->format('M d')));

            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Mood Score',
                    data: moodData
                }],
                xaxis: {
                    categories: moodDates,
                },
                yaxis: {
                    min: 1,
                    max: 10,
                    tickAmount: 9
                },
                colors: ['#0d9488'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.0,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
            }

            const chart = new ApexCharts(document.querySelector("#moodChart"), options);
            chart.render();
            
            Livewire.on('mood-logged', () => {
                // Ideally we refresh the component or chart data here
                window.location.reload();
            });
        });
    </script>
    <livewire:pre-consultation-chat :key="'pre-chat-modal-patient'" />
</div>
