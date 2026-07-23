<div class="flex flex-col h-full">
    <!-- Header -->
    <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800/90">
        <h3 class="text-white font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Session Notes
        </h3>
        <button @click="notesOpen = false" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- Notes Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4">
        
        @if (session()->has('message'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-3 py-2 rounded text-xs mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 shadow-sm mb-4">
            <form wire:submit.prevent="saveNote">
                <textarea wire:model="newNote" rows="3" class="w-full bg-gray-700 border-transparent text-white text-sm rounded-lg focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 resize-none" placeholder="Type a note about this session... (Private to you)"></textarea>
                <div class="mt-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors">
                        Save Note
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-3">
            @forelse($notes as $note)
                <div class="bg-gray-700/50 rounded-lg p-3 border border-gray-600">
                    <div class="text-xs text-gray-400 mb-1 flex justify-between">
                        <span>{{ $note->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-200 whitespace-pre-wrap">{{ $note->getContent() }}</p>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-gray-400 text-sm">No notes recorded for this session yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
