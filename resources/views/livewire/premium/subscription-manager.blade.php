<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Premium Memberships') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('error'))
            <div class="mb-8 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 rounded-xl px-4 py-3 text-sm flex items-center space-x-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                Unlock your full potential
            </h1>
            <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                Choose the plan that fits your needs, whether you are looking for advanced wellness tools or building your therapy practice.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            {{-- Patient Premium --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-8 flex-1">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Premium User</h3>
                    <p class="mt-4 flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-5xl font-extrabold tracking-tight">DZD9.99</span>
                        <span class="ml-1 text-xl font-medium text-gray-500 dark:text-gray-400">/month</span>
                    </p>
                    <p class="mt-6 text-gray-500 dark:text-gray-400">For users who want advanced tools and unlimited support.</p>
                    
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Unlimited Anonymous Matches
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Advanced Mood Tracking Insights
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Exclusive Wellness Workshops
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Personalized AI Recommendations
                        </li>
                    </ul>
                </div>
                <div class="p-8 bg-gray-50 dark:bg-gray-900/50">
                    <button wire:click="subscribe('premium')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition">
                        Subscribe Now
                    </button>
                </div>
            </div>

            {{-- Therapist Pro --}}
            <div class="bg-gradient-to-b from-indigo-500 to-indigo-700 rounded-2xl shadow-xl border border-indigo-700 overflow-hidden flex flex-col transform md:-translate-y-4">
                <div class="p-8 flex-1">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-2xl font-semibold text-white">Therapist Pro</h3>
                        <span class="px-3 py-1 bg-indigo-400 text-indigo-900 text-xs font-bold uppercase rounded-full tracking-wider">Most Popular</span>
                    </div>
                    
                    <p class="mt-4 flex items-baseline text-white">
                        <span class="text-5xl font-extrabold tracking-tight">DZD49.99</span>
                        <span class="ml-1 text-xl font-medium text-indigo-200">/month</span>
                    </p>
                    <p class="mt-6 text-indigo-100">For mental health professionals building their online practice.</p>
                    
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center text-indigo-50">
                            <svg class="w-5 h-5 text-indigo-200 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Top Placement in Directory
                        </li>
                        <li class="flex items-center text-indigo-50">
                            <svg class="w-5 h-5 text-indigo-200 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Unlimited Video Consultations
                        </li>
                        <li class="flex items-center text-indigo-50">
                            <svg class="w-5 h-5 text-indigo-200 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Advanced Analytics & Export
                        </li>
                        <li class="flex items-center text-indigo-50">
                            <svg class="w-5 h-5 text-indigo-200 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Zero Commission on First 10 Bookings
                        </li>
                    </ul>
                </div>
                <div class="p-8 bg-indigo-800">
                    <button wire:click="subscribe('pro')" class="w-full bg-white hover:bg-gray-100 text-indigo-700 font-bold py-3 px-4 rounded-xl transition">
                        Get Started
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
