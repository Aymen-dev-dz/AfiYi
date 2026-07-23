<div wire:poll.10s class="absolute -top-1 -right-1">
    @if($unreadCount > 0)
        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white shadow-sm border border-white dark:border-gray-800">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    @endif
</div>
