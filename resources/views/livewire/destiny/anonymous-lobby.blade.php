<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Destiny Connection Lobby') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-indigo-100 dark:border-indigo-900">
            <div class="bg-indigo-600 px-6 py-8 text-center text-white">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h1 class="text-3xl font-extrabold tracking-tight">Welcome to Destiny Connection</h1>
                <p class="mt-2 text-indigo-100 max-w-2xl mx-auto">
                    A safe, anonymous space to connect with someone else. Choose how you're feeling right now, and we'll pair you with a companion for a private chat.
                </p>
            </div>

            <div class="p-8">
                @if(!$isSearching)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Matching Mode</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $modes = [
                                        'random' => '🎲 Random',
                                        'mood' => '😊 Mood',
                                        'interest' => '⭐ Interest',
                                        'language' => '🗣 Language'
                                    ];
                                @endphp
                                @foreach($modes as $val => $label)
                                    <button wire:click="$set('mode', '{{ $val }}')" 
                                        class="px-4 py-3 rounded-xl border-2 transition font-medium text-sm
                                        {{ $mode === $val ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                            @error('mode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if($mode === 'mood')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">How are you feeling?</label>
                                <select wire:model="topic" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="">Select your mood...</option>
                                    <option value="Happy">Happy</option>
                                    <option value="Sad">Sad</option>
                                    <option value="Anxious">Anxious</option>
                                    <option value="Exhausted">Exhausted</option>
                                    <option value="Need to talk">Need someone</option>
                                </select>
                                @error('topic') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @elseif($mode === 'interest')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">What is your interest?</label>
                                <input wire:model="topic" type="text" placeholder="e.g. Dealing with burnout, Want to share good news..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('topic') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @elseif($mode === 'language')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Select Language</label>
                                <select wire:model="topic" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="">Select a language...</option>
                                    <option value="English">English</option>
                                    <option value="French">Français</option>
                                    <option value="Spanish">Español</option>
                                    <option value="German">Deutsch</option>
                                </select>
                                @error('topic') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="pt-4 flex justify-center">
                            <button wire:click="startSearch" class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Find Connection
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12" wire:poll.2s="checkMatch">
                        <div class="inline-block relative w-20 h-20">
                            <div class="absolute top-0 left-0 w-full h-full rounded-full border-4 border-indigo-100 border-t-indigo-600 animate-spin"></div>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-gray-800 dark:text-gray-100">Searching for a companion...</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2 mb-8">We are looking for someone to pair you with. Please wait.</p>
                        
                        <button wire:click="cancelSearch" class="px-6 py-2 border-2 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-semibold rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel Search
                        </button>
                    </div>
                @endif
            </div>

            <div class="bg-gray-50 dark:bg-gray-900/50 p-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Safety Rules</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1 list-disc list-inside">
                    <li>Be kind and respectful.</li>
                    <li>Do not share personal information (phone numbers, addresses).</li>
                    <li>Conversations are monitored by AI for safety.</li>
                    <li>Use the "Report" or "Block" button if you feel uncomfortable.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

