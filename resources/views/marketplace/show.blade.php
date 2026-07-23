<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <a href="{{ route('marketplace.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors">Marketplace</a>
            <span class="text-gray-400">/</span>
            <span>{{ $product->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Product Image -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-8 flex items-center justify-center min-h-[400px]">
                    @if($product->main_image)
                        <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="max-w-full h-auto rounded-xl shadow-md object-cover max-h-[500px]">
                    @else
                        <svg class="w-32 h-32 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    @if($product->category)
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 text-xs font-bold rounded-full uppercase tracking-wide mb-4 self-start">
                            {{ $product->category }}
                        </span>
                    @endif
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($product->price ?? 0, 2) }}</span>
                        @if($product->quantity > 0)
                            <span class="text-green-600 dark:text-green-400 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                In Stock
                            </span>
                        @else
                            <span class="text-red-600 dark:text-red-400 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <div class="prose prose-purple dark:prose-invert mb-8">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <form action="{{ route('cart.items.add') }}" method="POST" class="space-y-6 mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Variants block removed since table does not exist -->

                        <div class="flex gap-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Qty</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity ?: 99 }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            
                            <div class="flex-1 self-end">
                                <button type="submit" @if($product->quantity <= 0) disabled @endif class="w-full h-10 mt-[28px] bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors shadow-sm flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $product->quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Seller Info -->
                    @if($product->seller)
                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                    @if($product->seller->profile_photo_url)
                                        <img src="{{ $product->seller->profile_photo_url }}" alt="{{ $product->seller->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-500 font-medium">{{ substr($product->seller->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Sold by {{ $product->seller->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Wellness Partner</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($related->count() > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    You might also like
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($related as $relProduct)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group">
                            <a href="{{ route('marketplace.show', $relProduct->slug) }}" class="block relative h-40 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                @if($relProduct->main_image)
                                    <img src="{{ Storage::url($relProduct->main_image) }}" alt="{{ $relProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('marketplace.show', $relProduct->slug) }}">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-1 truncate hover:text-purple-600 transition-colors">{{ $relProduct->name }}</h4>
                                </a>
                                <p class="text-sm font-semibold text-purple-600 dark:text-purple-400">${{ number_format($relProduct->price ?? 0, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
