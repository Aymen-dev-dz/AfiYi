<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</h3>
            <p class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</h3>
            <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</h3>
            <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($stats['revenue'], 2) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Orders Management</h2>
        
        <div class="flex gap-4 w-full sm:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search reference or user..." class="w-full sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
            
            <select wire:model.live="filterStatus" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->reference }}</span>
                            <div class="text-xs text-gray-500">{{ $order->items->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ optional($order->user)->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ optional($order->user)->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                            ${{ number_format($order->total_price, 2) }}
                            <div class="text-[10px] mt-1 uppercase tracking-wider">
                                @if($order->notes && str_contains($order->notes, '"payment_method":"cod"'))
                                    <span class="text-amber-600 dark:text-amber-500 font-black">📦 À la livraison</span>
                                @else
                                    <span class="text-indigo-500 dark:text-indigo-400 font-black">💳 Carte</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->status === 'completed' || $order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($order->status === 'cancelled' || $order->status === 'refunded') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @endif
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
                            <button wire:click="openStatusModal({{ $order->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">Update Status</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            No orders found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Status Update Modal -->
    <x-dialog-modal wire:model.live="showStatusModal">
        <x-slot name="title">
            {{ __('Update Order Status') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Status</label>
                    <select wire:model="newStatus" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Comment (Optional)</label>
                    <textarea wire:model="comment" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Add a note about this status change..."></textarea>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showStatusModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateStatus" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
