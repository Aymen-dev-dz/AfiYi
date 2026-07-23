<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight flex items-center gap-3">
            <span class="text-emerald-500">🎉</span>
            {{ __('Commande Confirmée') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8 text-center relative">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-500/5 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-teal-500/10 dark:bg-teal-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[3rem] shadow-2xl overflow-hidden border border-white/50 dark:border-white/5 p-8 md:p-12 relative z-10">
            <!-- Decorative gradient blur -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-teal-500/20 rounded-full blur-3xl"></div>

            <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-emerald-500/30 relative">
                <div class="absolute inset-0 bg-white/20 rounded-full animate-ping" style="animation-duration: 3s;"></div>
                <svg class="w-12 h-12 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">Merci pour votre commande !</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 max-w-lg mx-auto font-medium">
                Votre commande <span class="font-bold text-emerald-600 dark:text-emerald-400">#{{ $order->reference }}</span> a été validée avec succès. 
                Un e-mail de confirmation a été envoyé à <span class="font-bold text-gray-900 dark:text-white">{{ Auth::user()->email }}</span>.
            </p>

            <!-- Order summary card -->
            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-md rounded-2xl p-8 text-left mb-10 max-w-md mx-auto border border-white/50 dark:border-gray-700/50 shadow-inner">
                <h3 class="font-black text-gray-900 dark:text-white mb-6 text-sm uppercase tracking-widest flex items-center gap-2">
                    <span class="text-emerald-500">📋</span> Détails de la commande
                </h3>
                <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                    <div class="flex justify-between items-center">
                        <span>Date</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Montant total</span>
                        <span class="font-black text-lg text-gray-900 dark:text-white">{{ number_format($order->total_price, 2) }} DZD</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Mode de paiement</span>
                        <span class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-lg">Carte Bancaire</span>
                    </div>
                </div>
            </div>

            @php
                $destinyItems = $order->items->filter(fn($item) => !empty($item->destiny_token));
            @endphp
            @if($destinyItems->isNotEmpty())
                <div class="mb-10 max-w-md mx-auto space-y-4">
                    @foreach($destinyItems as $item)
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl flex flex-col items-center">
                            <h3 class="font-black text-lg mb-2">Code Destiny Connection</h3>
                            <div class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-sm font-mono text-xl tracking-widest font-bold mb-4 w-full text-center border border-white/30">
                                {{ $item->destiny_token }}
                            </div>
                            <div class="flex flex-col gap-3 w-full">
                                <a href="{{ route('destiny.connect', ['token' => $item->destiny_token]) }}" class="w-full text-center py-4 bg-white hover:bg-gray-50 text-indigo-600 rounded-xl text-sm font-black shadow-lg transition-all hover:scale-[1.02] duration-200 uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span>🚀 Activer ma connexion</span>
                                </a>
                                <a href="{{ route('destiny.qrcode.download', $item->destiny_token) }}" target="_blank" class="w-full text-center py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 border border-white/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Télécharger le QR Code
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('marketplace.index') }}" class="w-full sm:w-auto px-8 py-4 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 font-bold rounded-2xl transition-all hover:bg-white dark:hover:bg-gray-800 shadow-sm">
                    Retour à la boutique
                </a>
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-black hover:to-gray-900 dark:from-indigo-600 dark:to-purple-600 text-white font-bold rounded-2xl transition-all shadow-xl hover:scale-[1.02]">
                    Mon tableau de bord
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
