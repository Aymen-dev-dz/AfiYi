<div class="flex flex-col h-full">
    <!-- Header -->
    <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800/90">
        <h3 class="text-white font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Session Chat
        </h3>
        <button @click="chatOpen = false" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages" wire:poll.4s="loadMessages" x-init="$watch('chatOpen', val => { if(val) setTimeout(() => { let el = document.getElementById('chat-messages'); el.scrollTop = el.scrollHeight; }, 100); })">
        
        <div class="flex justify-center">
            <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded-full">Encrypted Session</span>
        </div>
        
        @foreach($messages as $msg)
            @if($msg['is_system'])
                <div class="flex justify-center">
                    <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded-full">{{ $msg['message'] }}</span>
                </div>
            @else
                @if($msg['sender_id'] === auth()->id())
                    <div class="flex items-end gap-2 justify-end">
                        <div class="bg-purple-600 text-white px-3 py-2 rounded-2xl rounded-br-none text-sm max-w-[85%]">
                            {{ $msg['message'] }}
                            <div class="text-[10px] text-purple-200 text-right mt-1">{{ $msg['created_at'] }}</div>
                        </div>
                    </div>
                @else
                    <div class="flex items-end gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex-shrink-0 flex items-center justify-center text-xs text-white">
                            {{ substr($msg['sender_name'], 0, 1) }}
                        </div>
                        <div class="bg-gray-700 text-white px-3 py-2 rounded-2xl rounded-bl-none text-sm max-w-[85%]">
                            {{ $msg['message'] }}
                            <div class="text-[10px] text-gray-400 mt-1">{{ $msg['created_at'] }}</div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach

        <!-- Livewire listener to scroll to bottom when a new message arrives -->
        <span x-data="{
            init() {
                Livewire.on('message-sent', () => {
                    let el = document.getElementById('chat-messages');
                    setTimeout(() => el.scrollTop = el.scrollHeight, 100);
                });
            }
        }"></span>
    </div>

    <!-- Input Area -->
    <div class="p-4 border-t border-gray-700 bg-gray-800">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input type="text" wire:model="newMessage" placeholder="Type a message..." class="flex-1 bg-gray-700 border-transparent text-white text-sm rounded-xl focus:border-purple-500 focus:ring-purple-500 placeholder-gray-400" required>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-2 transition-colors">
                <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('morph.updated', ({ component, el }) => {
            if(component.name === 'teletherapy.teletherapy-chat') {
                let messagesDiv = document.getElementById('chat-messages');
                if (messagesDiv) {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            }
        });
    });
</script>
