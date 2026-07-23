<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Therapist Profile') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-8 max-w-5xl mx-auto">

        {{-- ── SUCCESS ALERT ─────────────────────────────────────────────── --}}
        <div x-data="{ show: false }"
            x-on:profile-saved.window="show = true; setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-6 right-6 z-50 bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-xl flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Profile saved successfully!</span>
        </div>

        {{-- ── TOP STATUS BANNER ──────────────────────────────────────────── --}}
        @if(!$profileId)
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-xl p-4 flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Profile Incomplete</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-0.5">
                        Complete your profile to become visible to patients in the directory.
                    </p>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8">

            {{-- ── SECTION 1: PHOTO ─────────────────────────────────────── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-5 flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm font-bold">1</span>
                    <span>Profile Photo</span>
                </h3>

                <div class="flex items-center space-x-6">
                    {{-- Current / Preview --}}
                    <div class="shrink-0">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                class="w-24 h-24 rounded-full object-cover ring-4 ring-indigo-200 dark:ring-indigo-700">
                        @elseif($photoPath)
                            <img src="{{ Storage::url($photoPath) }}" alt="Profile photo"
                                class="w-24 h-24 rounded-full object-cover ring-4 ring-indigo-200 dark:ring-indigo-700">
                        @else
                            <div class="w-24 h-24 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-3xl font-bold text-indigo-600 dark:text-indigo-300 ring-4 ring-indigo-200 dark:ring-indigo-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="photo-upload"
                            class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Choose Photo
                        </label>
                        <input id="photo-upload" type="file" wire:model="photo" class="hidden" accept="image/*">
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">JPG, PNG, GIF · max 2 MB</p>
                        @error('photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="photo" class="mt-1 text-xs text-indigo-500">Uploading…</div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: PROFESSIONAL INFORMATION ────────────────── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 space-y-5">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm font-bold">2</span>
                    <span>Professional Information</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Professional Title
                        </label>
                        <input wire:model="title" type="text"
                            placeholder="e.g. Licensed Psychologist"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Experience Years --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Years of Experience
                        </label>
                        <input wire:model="experienceYears" type="number" min="0" max="60"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('experienceYears') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- License Number --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            License Number
                        </label>
                        <input wire:model="licenseNumber" type="text"
                            placeholder="e.g. PSY-2024-00123"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('licenseNumber') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- License Issuer --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Issuing Authority
                        </label>
                        <input wire:model="licenseIssuer" type="text"
                            placeholder="e.g. Ordre des Psychologues"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('licenseIssuer') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Biography <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal ml-1">(min. 50 characters)</span>
                    </label>
                    <textarea wire:model="bio" rows="6"
                        placeholder="Tell patients about your background, training, and therapeutic approach…"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-y"></textarea>
                    <div class="flex justify-between mt-1">
                        @error('bio')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <span class="text-xs text-gray-400">{{ strlen($bio) }} / 3000</span>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: SPECIALTIES & SERVICES ──────────────────── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 space-y-5">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm font-bold">3</span>
                    <span>Specialties & Services</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Session Price --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Session Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">DZD</span>
                            <input wire:model="sessionPrice" type="number" min="0" step="0.01"
                                class="w-full pl-7 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        @error('sessionPrice') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Session Duration --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Session Duration <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input wire:model="sessionDurationMinutes" type="number" min="15" max="240" step="5"
                                class="w-full pr-14 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">min</span>
                        </div>
                        @error('sessionDurationMinutes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Specialties --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Specialties
                        <span class="text-gray-400 font-normal ml-1">(comma-separated)</span>
                    </label>
                    <input wire:model="specialtiesInput" type="text"
                        placeholder="e.g. Anxiety, Depression, Trauma, EMDR"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @if($specialtiesInput)
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach(array_filter(array_map('trim', explode(',', $specialtiesInput))) as $tag)
                                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-xs font-medium">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Languages --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Languages Spoken
                        <span class="text-gray-400 font-normal ml-1">(comma-separated)</span>
                    </label>
                    <input wire:model="languagesInput" type="text"
                        placeholder="e.g. English, French, Arabic"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @if($languagesInput)
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach(array_filter(array_map('trim', explode(',', $languagesInput))) as $tag)
                                <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-xs font-medium">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Approaches --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Therapeutic Approaches
                        <span class="text-gray-400 font-normal ml-1">(comma-separated)</span>
                    </label>
                    <input wire:model="approachesInput" type="text"
                        placeholder="e.g. CBT, ACT, Psychodynamic, Mindfulness"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @if($approachesInput)
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach(array_filter(array_map('trim', explode(',', $approachesInput))) as $tag)
                                <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded text-xs font-medium">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── SECTION 4: AVAILABILITY & SETTINGS ─────────────────── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 space-y-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm font-bold">4</span>
                    <span>Availability & Settings</span>
                </h3>

                {{-- Toggle: Accepts New Clients --}}
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer group">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                            Accepting New Clients
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Patients can book a first session with you
                        </p>
                    </div>
                    <button type="button" wire:click="$toggle('acceptsNewClients')"
                        class="relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                            {{ $acceptsNewClients ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                        <span class="sr-only">Toggle</span>
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                            {{ $acceptsNewClients ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </label>

                {{-- Toggle: Offers First Free Session --}}
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer group">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                            Free First Session
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Offer a complimentary discovery session to new patients
                        </p>
                    </div>
                    <button type="button" wire:click="$toggle('offersFirstFreeSession')"
                        class="relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                            {{ $offersFirstFreeSession ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                        <span class="sr-only">Toggle</span>
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                            {{ $offersFirstFreeSession ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </label>
            </div>

            {{-- ── SUBMIT BUTTON ────────────────────────────────────────── --}}
            <div class="flex justify-end">
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Profile
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Saving…
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>
