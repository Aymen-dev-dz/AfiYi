<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Therapist Applications</h2>
        
        <div class="flex gap-4 w-full sm:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search therapists..." class="w-full sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
            
            <select wire:model.live="filterStatus" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Therapist</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credentials</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applied On</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($therapists as $profile)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ optional($profile->user)->profile_photo_url }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ optional($profile->user)->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ optional($profile->user)->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $profile->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $profile->specialties ? implode(', ', $profile->specialties) : 'No specialties listed' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($profile->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Approved</span>
                            @elseif($profile->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                            @elseif($profile->status === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Rejected</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">{{ ucfirst($profile->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $profile->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @if($profile->status === 'pending')
                                <button wire:click="approve({{ $profile->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Approve</button>
                                <button wire:click="openRejectModal({{ $profile->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Reject</button>
                            @elseif($profile->status === 'approved')
                                <button wire:click="suspend({{ $profile->id }})" class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300">Suspend</button>
                            @elseif($profile->status === 'suspended')
                                <button wire:click="approve({{ $profile->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Unsuspend</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            No therapist applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $therapists->links() }}
        </div>
    </div>

    <!-- Reject Modal -->
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ __('Reject Therapist Application') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for rejection</label>
                <textarea wire:model="rejectionReason" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Please provide a reason..."></textarea>
                @error('rejectionReason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="reject" wire:loading.attr="disabled">
                {{ __('Reject') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
