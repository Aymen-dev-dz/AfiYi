<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la commande #') }}{{ $order->order_number ?? $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informations de la Commande</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Client</h4>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-gray-100">{{ $order->user->name ?? 'Client inconnu' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Adresse de livraison</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line">{{ is_array($order->shipping_address) ? implode(', ', array_filter($order->shipping_address)) : ($order->shipping_address ?? 'Non spécifiée') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Articles commandés (Vos produits)</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produit</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix unitaire</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->product_name }}
                                        @if($item->is_digital)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                Digital
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($item->price, 2) }} DZD
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($item->price * $item->quantity, 2) }} DZD
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100">Total pour vos produits :</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-black text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2) }} DZD
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('seller.orders.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-bold text-sm">
                        Retour aux commandes
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
