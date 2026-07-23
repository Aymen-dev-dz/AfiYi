<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details: ') . $order->reference }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Order Summary</h3>
            <p class="text-gray-600 dark:text-gray-300">Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>
            <p class="text-gray-600 dark:text-gray-300">Total: DZD{{ number_format($order->total, 2) }}</p>
            <p class="text-gray-600 dark:text-gray-300">Placed on: {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Items</h3>
            
            <div class="space-y-6">
                @foreach($order->items as $item)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Qty: {{ $item->quantity }} | DZD{{ number_format($item->subtotal, 2) }}</p>
                            @if($item->variant_label)
                                <p class="text-xs text-gray-400 mt-1">Variant: {{ $item->variant_label }}</p>
                            @endif
                        </div>

                        
                            </div>
                        @else
                            
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
