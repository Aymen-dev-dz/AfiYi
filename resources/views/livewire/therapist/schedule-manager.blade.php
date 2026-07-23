<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Schedule') }}
        </h2>
    </x-slot>

    @php
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $dayShort = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $dayColors = [
            0 => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300',
            1 => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
            2 => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300',
            3 => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
            4 => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
            5 => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
            6 => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
        ];
    @endphp

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-8 max-w-5xl mx-auto">

        {{-- ── ALERTS ───────────────────────────────────────────────────── --}}
        @if(!$hasProfile)
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-xl p-4 flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-sm text-amber-800 dark:text-amber-200">
                    Please <a href="{{ route('therapist.profile') }}" class="underline font-semibold">complete your profile</a> before managing your schedule.
                </p>
            </div>
        @endif

        @if($successMessage)
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
                x-transition
                class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 rounded-xl px-4 py-3 text-sm flex items-center space-x-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
        @endif

        @if($errorMessage)
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 rounded-xl px-4 py-3 text-sm flex items-center space-x-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        {{-- ── WEEKLY SCHEDULE CARD ─────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow">
            <div class="flex items-center justify-between px-6 py-5 border-b dark:border-gray-700">
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Weekly Availability</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Define your recurring available hours for each day of the week.
                    </p>
                </div>
                <button wire:click="openAddSlot"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Slot
                </button>
            </div>

            @if($schedules->isEmpty())
                <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                    <svg class="w-14 h-14 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-medium">No availability set</p>
                    <p class="text-sm mt-1">Click "Add Slot" to define your working hours.</p>
                </div>
            @else
                <div class="p-6">
                    {{-- Day-by-day view --}}
                    <div class="space-y-3">
                        @for($d = 0; $d <= 6; $d++)
                            @if(isset($schedules[$d]) && $schedules[$d]->isNotEmpty())
                                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40">
                                    <div class="w-24 shrink-0 pt-0.5">
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg {{ $dayColors[$d] }}">
                                            {{ $dayNames[$d] }}
                                        </span>
                                    </div>
                                    <div class="flex-1 flex flex-wrap gap-2">
                                        @foreach($schedules[$d] as $slot)
                                            <div class="inline-flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm text-gray-700 dark:text-gray-200 shadow-sm">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ substr($slot->start_time, 0, 5) }} – {{ substr($slot->end_time, 0, 5) }}
                                                <button wire:click="deleteSlot({{ $slot->id }})"
                                                    wire:confirm="Remove this time slot?"
                                                    class="ml-2 text-gray-300 hover:text-red-500 dark:hover:text-red-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endfor
                    </div>

                    {{-- Summary table --}}
                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 font-semibold border-b dark:border-gray-700">
                                    <th class="pb-2 pr-4">Day</th>
                                    <th class="pb-2 pr-4">Start</th>
                                    <th class="pb-2 pr-4">End</th>
                                    <th class="pb-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($schedules->flatten() as $slot)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                        <td class="py-2.5 pr-4 font-medium text-gray-700 dark:text-gray-200">
                                            <span class="px-2 py-0.5 text-xs rounded {{ $dayColors[$slot->day_of_week] }}">
                                                {{ $dayNames[$slot->day_of_week] }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 pr-4 text-gray-600 dark:text-gray-300">{{ substr($slot->start_time, 0, 5) }}</td>
                                        <td class="py-2.5 pr-4 text-gray-600 dark:text-gray-300">{{ substr($slot->end_time, 0, 5) }}</td>
                                        <td class="py-2.5">
                                            <button wire:click="deleteSlot({{ $slot->id }})"
                                                wire:confirm="Remove this time slot?"
                                                class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium transition">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── UNAVAILABILITIES CARD ────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow">
            <div class="px-6 py-5 border-b dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Unavailability Periods</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Block out specific dates — vacations, conferences, personal leave.
                </p>
            </div>

            <div class="p-6 space-y-6">

                {{-- Add Unavailability Form --}}
                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-5">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Add Unavailability</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="unavailStart" type="date"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('unavailStart') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                End Date <span class="text-gray-400">(optional, defaults to start)</span>
                            </label>
                            <input wire:model="unavailEnd" type="date"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('unavailEnd') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Reason
                            </label>
                            <input wire:model="unavailReason" type="text"
                                placeholder="e.g. Vacation, Conference"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('unavailReason') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="addUnavailability"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-5 py-2 bg-gray-800 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Period
                        </button>
                    </div>
                </div>

                {{-- Unavailability List --}}
                @if($unavailabilities->isEmpty())
                    <div class="text-center py-10 text-gray-400 dark:text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <p class="text-sm font-medium">No blocked periods</p>
                        <p class="text-xs mt-1">Use the form above to add unavailability dates.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 font-semibold border-b dark:border-gray-700">
                                    <th class="pb-2 pr-4">Start Date</th>
                                    <th class="pb-2 pr-4">End Date</th>
                                    <th class="pb-2 pr-4">Reason</th>
                                    <th class="pb-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($unavailabilities as $unavail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                        <td class="py-3 pr-4 text-gray-700 dark:text-gray-200 font-medium">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>
                                                <span>{{ $unavail->start_date->format('M d, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $unavail->end_date && $unavail->end_date != $unavail->start_date
                                                ? $unavail->end_date->format('M d, Y')
                                                : '—' }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-500 dark:text-gray-400">
                                            {{ $unavail->reason ?? '—' }}
                                        </td>
                                        <td class="py-3">
                                            <button wire:click="deleteUnavailability({{ $unavail->id }})"
                                                wire:confirm="Remove this unavailability period?"
                                                class="inline-flex items-center text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── ADD SLOT MODAL ───────────────────────────────────────────────── --}}
    @if($showAddSlotModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-data x-on:keydown.escape.window="$wire.showAddSlotModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Add Availability Slot</h3>
                    <button wire:click="$set('showAddSlotModal', false)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Day --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Day of Week <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="slotDay"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="0">Sunday</option>
                        </select>
                        @error('slotDay') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Start --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="slotStart" type="time"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('slotStart') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- End --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="slotEnd" type="time"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('slotEnd') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button wire:click="$set('showAddSlotModal', false)"
                        class="px-5 py-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="saveSlot"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveSlot">Save Slot</span>
                        <span wire:loading wire:target="saveSlot">Saving…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
