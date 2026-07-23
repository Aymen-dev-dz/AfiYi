<div x-data @open-pre-consultation-chat.window="$wire.openChat($event.detail.id)">
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-lg mx-4 flex flex-col h-[600px] overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-150 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl">
                        💬
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Chat Pré-Consultation</h3>
                        <p class="text-[10px] text-slate-500">Posez vos questions avant la séance</p>
                    </div>
                </div>
                <button wire:click="closeChat" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 dark:bg-slate-900/20" id="pre-consultation-chat-messages" wire:poll.5s="loadMessages">
                @forelse($messages as $msg)
                    @php
                        $isMe = $msg['sender_id'] === Auth::id();
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] {{ $isMe ? 'bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-r-2xl rounded-tl-2xl' }} p-3 shadow-sm relative group">
                            <p class="text-sm">{{ $msg['message'] }}</p>
                            <span class="text-[9px] opacity-50 block mt-1 {{ $isMe ? 'text-right text-indigo-100' : 'text-left text-slate-400' }}">
                                {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-3">
                        <span class="text-4xl">👋</span>
                        <p class="text-xs font-medium text-center">Aucun message pour le moment.<br>Écrivez pour commencer l'échange.</p>
                    </div>
                @endforelse
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-gray-150 dark:border-gray-700 bg-white dark:bg-gray-800">
                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                    <input type="text" wire:model.defer="newMessage" placeholder="Écrivez votre message..." class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-sm rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white dark:placeholder-gray-400" required>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-5 py-3 shadow-sm font-bold flex items-center justify-center transition" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendMessage">Envoyer</span>
                        <span wire:loading wire:target="sendMessage">...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scroll to bottom script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollContainer = () => {
                const container = document.getElementById('pre-consultation-chat-messages');
                if (container) container.scrollTop = container.scrollHeight;
            };
            
            // Scroll on open
            Livewire.on('openPreConsultationChat', () => {
                setTimeout(scrollContainer, 100);
            });
            
            // Scroll on morph update
            Livewire.hook('morph.updated', ({ component }) => {
                if (component.name === 'pre-consultation-chat') {
                    scrollContainer();
                }
            });
        });
    </script>
    @endif
</div>
