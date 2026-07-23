<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight flex items-center gap-3">
            <span class="text-indigo-500">🛒</span>
            {{ __('Votre Panier') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-violet-500/10 dark:bg-violet-500/5 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        @if(session('success'))
            <div class="mb-8 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 p-4 rounded-2xl shadow-lg shadow-emerald-500/10 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
                <div class="bg-emerald-100 dark:bg-emerald-800/50 p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[3rem] shadow-xl border border-white/50 dark:border-white/5 p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/5 to-purple-500/5"></div>
                <div class="relative z-10">
                    <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner relative">
                        <div class="absolute inset-0 bg-indigo-500/20 blur-xl rounded-full"></div>
                        <span class="text-5xl drop-shadow-md">🛒</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-3 tracking-tight">Votre panier est vide</h3>
                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-10 font-medium">Il semble que vous n'ayez pas encore ajouté de produits bien-être.</p>
                    <a href="{{ route('marketplace.index') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-black text-lg rounded-2xl transition-all shadow-xl shadow-indigo-500/30 hover:scale-105">
                        Explorer la Boutique
                    </a>
                </div>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8 relative z-10">
                <!-- Cart Items -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 overflow-hidden p-6">
                        <ul class="space-y-6">
                            @foreach($cart as $key => $item)
                                <li class="p-6 bg-white/50 dark:bg-gray-800/50 rounded-[2rem] border border-white/50 dark:border-gray-700/50 shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row items-center gap-6 group">
                                    <div class="w-28 h-28 bg-gray-100 dark:bg-gray-900 rounded-[1.5rem] overflow-hidden flex-shrink-0 flex items-center justify-center relative">
                                        @if(!empty($item['thumbnail']))
                                            <img src="{{ Storage::url($item['thumbnail']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <span class="text-4xl text-gray-300 dark:text-gray-600">📦</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 text-center sm:text-left">
                                        <h4 class="text-xl font-black text-gray-900 dark:text-white mb-2">{{ $item['name'] }}</h4>
                                        <div class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-widest">Prix U. : {{ number_format($item['price'], 2) }} DZD</div>
                                        
                                        <!-- Update Quantity -->
                                        <form action="{{ route('cart.items.update', $key) }}" method="POST" class="inline-flex items-center gap-3 bg-white dark:bg-gray-700 p-1 rounded-xl shadow-inner border border-gray-100 dark:border-gray-600">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="w-16 text-center border-none bg-transparent dark:text-white font-black focus:ring-0 px-2 py-1 h-8">
                                            <button type="submit" class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-200 transition-colors uppercase tracking-widest">Mettre à jour</button>
                                        </form>
                                    </div>

                                    <div class="text-right flex flex-col items-center sm:items-end justify-between h-full gap-4">
                                        <div>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1 text-right">Total</p>
                                            <div class="text-2xl font-black text-gray-900 dark:text-white">
                                                {{ number_format($item['price'] * $item['quantity'], 2) }} DZD
                                            </div>
                                        </div>
                                        <!-- Remove Item -->
                                        <form action="{{ route('cart.items.remove', $key) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors flex items-center gap-1.5 bg-red-50 dark:bg-red-900/20 px-3 py-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl border border-white dark:border-gray-700 p-8 sticky top-8">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-8 tracking-tight">Résumé de la commande</h3>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400 font-medium">
                                <span>Sous-total</span>
                                <span class="font-bold">{{ number_format($total, 2) }} DZD</span>
                            </div>

                            @if(session()->has('coupon'))
                                @php 
                                    $discount = $total * (session('coupon')['discount'] / 100); 
                                    $finalTotal = $total - $discount;
                                @endphp
                                <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-medium">
                                    <span>Remise ({{ session('coupon')['code'] }})</span>
                                    <span class="font-bold">-{{ number_format($discount, 2) }} DZD</span>
                                </div>
                            @else
                                @php $finalTotal = $total; @endphp
                            @endif

                            <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-gray-900 dark:text-white font-bold">Total</span>
                                    <span class="text-4xl font-black text-gray-900 dark:text-white">{{ number_format($finalTotal, 2) }} <span class="text-xl">DZD</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Coupon Form -->
                        @if(!session()->has('coupon'))
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 mb-8 bg-gray-50 dark:bg-gray-900/50 p-1 rounded-xl border border-gray-100 dark:border-gray-700 shadow-inner">
                                @csrf
                                <input type="text" name="coupon" placeholder="Code promo" required class="flex-1 border-none bg-transparent dark:text-white focus:ring-0 px-4 py-2 font-medium">
                                <button type="submit" class="bg-gray-900 dark:bg-gray-600 hover:bg-gray-800 dark:hover:bg-gray-500 text-white rounded-lg px-6 py-2 font-bold uppercase tracking-widest text-xs transition-colors shadow-md">Appliquer</button>
                            </form>
                        @else
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-8">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 flex items-center justify-center gap-2 w-full py-3 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Retirer le code promo
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('checkout.index') }}" class="block w-full bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-center font-black py-4 rounded-2xl transition-all shadow-xl shadow-indigo-500/30 hover:scale-[1.02] flex items-center justify-center gap-2">
                            <span>Passer à la caisse</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
