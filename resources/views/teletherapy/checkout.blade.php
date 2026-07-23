<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Confirm Consultation') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Review and Pay</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">You're almost done! Please review your session details.</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-8 border border-gray-100 dark:border-gray-600">
                <div class="flex items-center gap-4 pb-6 mb-6 border-b border-gray-200 dark:border-gray-600">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden flex-shrink-0">
                        @if($consultation->therapistProfile->user->profile_photo_url)
                            <img src="{{ $consultation->therapistProfile->user->profile_photo_url }}" alt="{{ $consultation->therapistProfile->user->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $consultation->therapistProfile->user->name }}</h4>
                        <p class="text-purple-600 dark:text-purple-400 text-sm font-medium">{{ $consultation->therapistProfile->title ?? 'Therapist' }}</p>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Date
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('l, F j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Time & Duration
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('H:i') }} ({{ $consultation->duration_minutes }} min)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Type
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $consultation->type }} Session</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between py-4 border-t border-b border-gray-100 dark:border-gray-700 mb-8">
                <span class="text-lg font-bold text-gray-900 dark:text-white">Total to Pay</span>
                <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($consultation->price, 2) }}</span>
            </div>

            <!-- Fake Payment Form -->
            <form action="{{ route('teletherapy.success', $consultation->id) }}" method="GET">
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center gap-2 text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Simulated Payment Processing
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors flex justify-center items-center gap-2">
                    Pay ${{ number_format($consultation->price, 2) }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
