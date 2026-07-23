<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
                {{ __('Anonymous Room') }} 
                <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                    Partner: {{ $partnerNickname }}
                </span>
                
                {{-- AlpineJS Timer --}}
                <div x-data="{
                        startedAt: new Date('{{ $match->started_at->toISOString() }}').getTime(),
                        timeLeft: '15:00',
                        updateTimer() {
                            const now = new Date().getTime();
                            const diff = now - this.startedAt;
                            const maxTime = 15 * 60 * 1000; // 15 mins
                            const remaining = maxTime - diff;
                            if(remaining <= 0) {
                                this.timeLeft = '00:00';
                                $wire.endChat();
                            } else {
                                const m = Math.floor(remaining / 60000);
                                const s = Math.floor((remaining % 60000) / 1000);
                                this.timeLeft = m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');
                            }
                        }
                    }"
                    x-init="updateTimer(); setInterval(() => updateTimer(), 1000)"
                    class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 flex items-center"
                >
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="timeLeft"></span>
                </div>
            </h2>
            
            <div class="flex space-x-2">
                <button wire:click="reportUser" wire:confirm="Are you sure you want to report this user and end the chat?" class="text-xs px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300 rounded-lg transition font-semibold">
                    Report
                </button>
                <button wire:click="blockUser" wire:confirm="Are you sure you want to block this user and end the chat?" class="text-xs px-3 py-1.5 bg-orange-100 text-orange-700 hover:bg-orange-200 dark:bg-orange-900/40 dark:text-orange-300 rounded-lg transition font-semibold">
                    Block
                </button>
                <button wire:click="endChat" wire:confirm="End this chat session?" class="text-xs px-3 py-1.5 bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-lg transition font-semibold">
                    Leave
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" wire:poll.3s>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col" style="height: 70vh;">
            
            {{-- Topic Banner --}}
            @if($match->topic)
                <div class="bg-indigo-50 dark:bg-indigo-900/30 px-6 py-3 border-b border-indigo-100 dark:border-indigo-800 flex items-center justify-center">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300 font-medium">
                        <strong>Topic:</strong> {{ $match->topic }}
                    </span>
                </div>
            @endif

            @if($match->status === 'closed')
                <div class="bg-red-50 dark:bg-red-900/30 p-4 text-center text-red-600 dark:text-red-400 font-bold border-b border-red-200 dark:border-red-800">
                    This chat session has ended.
                </div>
            @endif

            {{-- Chat History --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-container">
                @if($messages->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-center space-y-6">
                        <div class="text-gray-400 dark:text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p>Say hello to start the conversation!</p>
                        </div>
                        
                        {{-- Ice Breakers --}}
                        <div class="w-full max-w-lg">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Ice Breakers</p>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($iceBreakers as $ice)
                                    <button wire:click="sendIceBreaker('{{ addslashes($ice) }}')" class="text-left px-4 py-2 text-sm bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 dark:text-indigo-300 rounded-lg transition">
                                        "{{ $ice }}"
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($messages as $msg)
                        @if($msg->sender_id === Auth::id())
                            {{-- Sent Message --}}
                            <div class="flex justify-end">
                                <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-5 py-2.5 max-w-[75%] shadow-sm">
                                    <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                    <p class="text-[10px] text-indigo-200 text-right mt-1">{{ $msg->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @else
                            {{-- Received Message --}}
                            <div class="flex justify-start">
                                <div class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm px-5 py-2.5 max-w-[75%] shadow-sm">
                                    <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">{{ $msg->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            {{-- Crisis Alert --}}
            @if($showCrisisAlert)
                <div class="m-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 shadow-sm animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Content Warning / Crisis Alert</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                <p>Your message was flagged by our safety system. If you are experiencing a mental health crisis or having thoughts of self-harm, please know that you are not alone and help is available.</p>
                                <ul class="mt-2 list-disc list-inside">
                                    <li><strong>Emergency:</strong> Please call 911 (US) or 112 (EU).</li>
                                    <li><strong>Support Line:</strong> Call or text 988 (US/Canada).</li>
                                </ul>
                                <div class="mt-4">
                                    <a href="{{ route('teletherapy.directory') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Find a Professional Therapist Here &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Message Input --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="sendMessage" class="flex items-end space-x-3">
                    <div class="flex-1">
                        <textarea wire:model="messageText" rows="1" placeholder="Type your message..." 
                            class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 resize-none py-3 px-4 text-sm"
                            {{ $match->status === 'closed' ? 'disabled' : '' }}
                            onkeydown="if(event.keyCode===13 && !event.shiftKey){ event.preventDefault(); @this.sendMessage(); }"></textarea>
                    </div>
                    <button type="submit" 
                        {{ $match->status === 'closed' ? 'disabled' : '' }}
                        class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow transition disabled:opacity-50">
                        <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V6m0 0l-7 7m7-7l7 7"></path>
                        </svg>
                    </button>
                </form>
                @error('messageText') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-container');
            if (container) container.scrollTop = container.scrollHeight;

            Livewire.hook('morph.updated', () => {
                if (container) container.scrollTop = container.scrollHeight;
            });
        });
    </script>
</div>

