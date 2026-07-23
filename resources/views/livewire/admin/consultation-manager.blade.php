<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Consultations') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/2">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by patient or therapist name..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            </div>
            <div class="w-full md:w-1/4">
                <select wire:model.live="statusFilter" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-semibold text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                        <th class="py-3 px-4">Patient</th>
                        <th class="py-3 px-4">Therapist</th>
                        <th class="py-3 px-4">Scheduled At</th>
                        <th class="py-3 px-4">Duration</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($consultations as $consultation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                {{ $consultation->patient->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                {{ $consultation->therapistProfile->user->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">
                                {{ $consultation->scheduled_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">
                                {{ $consultation->duration_minutes }} min
                            </td>
                            <td class="py-3 px-4">
                                @if($consultation->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Completed</span>
                                @elseif($consultation->status === 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Cancelled</span>
                                @elseif($consultation->status === 'in_progress')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">In Progress</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Scheduled</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $consultations->links() }}
        </div>
    </div>
</div>
