<x-app-layout>
    <!-- Header Banner -->
    <div class="relative w-full min-h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600"></div>
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-50/90 dark:from-gray-900/90 to-transparent"></div>
        
        <div class="relative z-10 text-center px-4 max-w-3xl mt-12">
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 drop-shadow-md">
                Boutique <span class="text-indigo-200">Bien-être</span>
            </h1>
            <p class="text-xl text-indigo-100 font-medium">Découvrez des produits soigneusement sélectionnés pour votre équilibre mental et émotionnel.</p>
        </div>
    </div>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 -mt-24 relative z-20">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4 space-y-6">
                <!-- Search -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl p-6 rounded-[2rem] shadow-xl border border-white/50 dark:border-gray-700/50">
                    <form action="{{ route('marketplace.index') }}" method="GET">
                        <label class="block text-xs font-black text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-widest">Rechercher</label>
                        <div class="relative mb-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Trouver un produit..." class="w-full bg-white/50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 pl-10 pr-4 font-medium transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:scale-[1.02]">
                            Rechercher
                        </button>
                    </form>
                </div>

                <!-- Shop by Need -->
                @php
                    $availableNeeds = ['Stress Relief' => '😌 Soulagement Stress', 'Better Sleep' => '😴 Meilleur Sommeil', 'Focus & Productivity' => '🎯 Concentration', 'Emotional Balance' => '🧘 Équilibre Émotionnel', 'Self-Care' => '✨ Soin de Soi', 'Meditation' => '🧘‍♀️ Méditation'];
                    $currentNeeds = explode(',', request('needs', ''));
                @endphp
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl p-6 rounded-[2rem] shadow-xl border border-white/50 dark:border-gray-700/50">
                    <h3 class="text-xs font-black text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                        <span class="text-indigo-500">✨</span> Filtrer par Besoin
                    </h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('marketplace.index') }}" class="block px-4 py-3 rounded-xl text-sm font-bold transition-all {{ !request('needs') ? 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20' : 'text-gray-600 dark:text-gray-300 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                            Tous les produits
                        </a>
                        @foreach($availableNeeds as $key => $label)
                            @php
                                $isActive = in_array($key, $currentNeeds);
                                $newNeeds = $isActive ? array_diff($currentNeeds, [$key]) : array_merge($currentNeeds, [$key]);
                                $newNeeds = array_filter($newNeeds);
                                $needsUrl = route('marketplace.index', array_merge(request()->query(), ['needs' => implode(',', $newNeeds)]));
                                if (empty($newNeeds)) {
                                    $query = request()->query();
                                    unset($query['needs']);
                                    $needsUrl = route('marketplace.index', $query);
                                }
                            @endphp
                            <a href="{{ $needsUrl }}" class="block px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $isActive ? 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20' : 'text-gray-600 dark:text-gray-300 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="w-full lg:w-3/4">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($products as $product)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-[2rem] shadow-lg border border-white/50 dark:border-gray-700/50 overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 group flex flex-col hover:-translate-y-1">
                            <a href="{{ route('marketplace.show', $product->slug) }}" class="relative h-56 block overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-t-[2rem]">
                                @if($product->main_image)
                                    <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-800 dark:to-gray-700">
                                        <svg class="w-16 h-16 text-indigo-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                
                                @if($product->is_featured)
                                    <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg shadow-orange-500/30 uppercase tracking-widest">
                                        Populaire
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                            <div class="p-6 flex flex-col flex-grow relative bg-white/50 dark:bg-gray-800/50">
                                <a href="{{ route('marketplace.show', $product->slug) }}">
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ $product->name }}</h3>
                                </a>
                                
                                @if(is_array($product->tags) && count($product->tags) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($product->tags, 0, 2) as $tag)
                                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full uppercase tracking-wider border border-indigo-100 dark:border-indigo-800">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif

                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 line-clamp-2 font-medium">{{ $product->description }}</p>
                                
                                <div class="mb-6 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 p-3 rounded-xl border border-purple-100 dark:border-purple-800/30 flex items-start gap-3 shadow-inner">
                                    <span class="text-purple-500 text-lg">✨</span>
                                    
                                </div>
                                
                                <div class="mt-auto flex items-end justify-between">
                                    <div>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Prix</p>
                                        <span class="text-3xl font-black text-gray-900 dark:text-white">{{ number_format($product->price ?? 0, 2) }} <span class="text-xl">DZD</span></span>
                                    </div>
                                    <form action="{{ route('cart.items.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-gray-100 hover:bg-indigo-600 text-gray-600 hover:text-white dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-indigo-500 w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-sm hover:shadow-lg hover:shadow-indigo-500/30 group/btn">
                                            <svg class="w-6 h-6 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white/70 dark:bg-gray-800/70 backdrop-blur-md rounded-[2rem] shadow-sm border border-white/50 dark:border-gray-700/50">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Aucun produit trouvé</h3>
                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-8">Essayez de modifier vos critères de recherche.</p>
                            <a href="{{ route('marketplace.index') }}" class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30">Réinitialiser les filtres</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
