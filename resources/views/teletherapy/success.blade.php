<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Confirmed') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8 text-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12">
            <div class="w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Your Session is Booked!</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                Your consultation <span class="font-bold">#{{ $consultation->reference }}</span> has been confirmed. 
                You will receive a calendar invitation shortly.
            </p>

            <div class="bg-purple-50 dark:bg-purple-900/30 border border-purple-100 dark:border-purple-800 rounded-xl p-6 text-left mb-8 max-w-md mx-auto">
                <div class="flex items-center gap-4 pb-4 border-b border-purple-200 dark:border-purple-700/50 mb-4">
                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden flex-shrink-0">
                        @if($consultation->therapistProfile->user->profile_photo_url)
                            <img src="{{ $consultation->therapistProfile->user->profile_photo_url }}" alt="{{ $consultation->therapistProfile->user->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $consultation->therapistProfile->user->name }}</h4>
                        <p class="text-purple-600 dark:text-purple-400 text-sm">{{ $consultation->therapistProfile->title ?? 'Therapist' }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('l, F j, Y') }}</p>
                            <p>{{ \Carbon\Carbon::parse($consultation->scheduled_at)->format('H:i') }} ({{ $consultation->duration_minutes }} min)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $consultation->type }} Session</p>
                            <p>You can join the session from your dashboard 5 minutes before it starts.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('teletherapy.room', $consultation->id) }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition-colors shadow-sm">
                    Enter Waiting Room
                </a>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
