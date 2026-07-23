<div wire:poll.10s class="inline-flex">
    @if($unreadCount > 0)
        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm animate-bounce">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    @endif
</div>
