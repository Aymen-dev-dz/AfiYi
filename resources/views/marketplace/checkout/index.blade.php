<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight flex items-center gap-3">
            <span class="text-indigo-500">🔒</span>
            {{ __('Paiement Sécurisé') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-violet-500/10 dark:bg-violet-500/5 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row gap-8 relative z-10" x-data="checkoutForm()">
            <!-- Left Column: Forms -->
            <div class="w-full lg:w-2/3 space-y-8">
                <!-- Contact Info -->
                <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xl font-black text-indigo-600 dark:text-indigo-400">1</div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Informations de Contact</h3>
                    </div>
                    <div class="bg-white/50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700/50 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-indigo-500/30">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-gray-900 dark:text-white">
                            <p class="font-bold text-lg">{{ Auth::user()->name }}</p>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xl font-black text-indigo-600 dark:text-indigo-400">2</div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Adresse de Livraison</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Prénom</label>
                            <input type="text" x-model="shipping.first_name" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Nom</label>
                            <input type="text" x-model="shipping.last_name" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Adresse</label>
                            <input type="text" x-model="shipping.address_line1" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Complément (Optionnel)</label>
                            <input type="text" x-model="shipping.address_line2" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Ville</label>
                            <input type="text" x-model="shipping.city" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Code Postal</label>
                            <input type="text" x-model="shipping.postal_code" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Pays</label>
                            <select x-model="shipping.country" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                                <option value="">Sélectionnez un pays</option>
                                <option value="FR">France</option>
                                <option value="BE">Belgique</option>
                                <option value="CH">Suisse</option>
                                <option value="CA">Canada</option>
                                <option value="US">États-Unis</option>
                                <option value="GB">Royaume-Uni</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
                        <input type="checkbox" id="same_billing" x-model="sameAsShipping" class="rounded-md border-indigo-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                        <label for="same_billing" class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300">L'adresse de facturation est identique à l'adresse de livraison</label>
                    </div>
                </div>

                <!-- Billing Address -->
                <div x-show="!sameAsShipping" x-transition.duration.300ms class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xl font-black text-indigo-600 dark:text-indigo-400">3</div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Adresse de Facturation</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Same inputs as shipping, bound to billing object -->
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Prénom</label>
                            <input type="text" x-model="billing.first_name" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Nom</label>
                            <input type="text" x-model="billing.last_name" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Adresse</label>
                            <input type="text" x-model="billing.address_line1" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Ville</label>
                            <input type="text" x-model="billing.city" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Code Postal</label>
                            <input type="text" x-model="billing.postal_code" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Pays</label>
                            <select x-model="billing.country" class="w-full bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 dark:text-white rounded-xl shadow-inner focus:border-indigo-500 focus:ring-indigo-500 py-3 font-medium transition-all">
                                <option value="">Sélectionnez un pays</option>
                                <option value="FR">France</option>
                                <option value="BE">Belgique</option>
                                <option value="CH">Suisse</option>
                                <option value="CA">Canada</option>
                                <option value="US">États-Unis</option>
                                <option value="GB">Royaume-Uni</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xl font-black text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Paiement</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border rounded-2xl cursor-pointer transition-all" :class="payment_method === 'card' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50'">
                            <input type="radio" x-model="payment_method" value="card" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-4 flex-1">
                                <span class="block font-bold text-gray-900 dark:text-white">Carte Bancaire (Simulé)</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Paiement sécurisé en ligne</span>
                            </span>
                            <span class="text-2xl">💳</span>
                        </label>
                        
                        <label class="flex items-center p-4 border rounded-2xl cursor-pointer transition-all" :class="payment_method === 'cod' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50'">
                            <input type="radio" x-model="payment_method" value="cod" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-4 flex-1">
                                <span class="block font-bold text-gray-900 dark:text-white">Paiement à la livraison</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Payer à la réception. Un administrateur vérifiera la commande.</span>
                            </span>
                            <span class="text-2xl">📦</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl border border-white dark:border-gray-700 p-8 sticky top-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-8 tracking-tight">Résumé de la commande</h3>
                    
                    <div class="space-y-4 mb-8 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($summary['items'] as $item)
                            <div class="flex justify-between items-start">
                                <div class="flex-1 pr-4">
                                    <span class="font-bold text-gray-900 dark:text-white line-clamp-1">{{ $item['name'] }}</span>
                                    <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">Qté : {{ $item['quantity'] }}</div>
                                </div>
                                <span class="font-black text-gray-900 dark:text-white">{{ number_format($item['price'] * $item['quantity'], 2) }} DZD</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400 font-medium">
                            <span>Sous-total</span>
                            <span class="font-bold">{{ number_format($summary['subtotal'], 2) }} DZD</span>
                        </div>
                        @if($summary['discount'] > 0)
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-medium">
                                <span>Remise</span>
                                <span class="font-bold">-{{ number_format($summary['discount'], 2) }} DZD</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-600 dark:text-gray-400 font-medium">
                            <span>Frais de port</span>
                            <span class="font-bold text-indigo-500">Calculés après</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-baseline pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 mb-8">
                        <span class="font-bold text-gray-900 dark:text-white text-xl">Total</span>
                        <span class="font-black text-4xl text-gray-900 dark:text-white">{{ number_format($summary['total'], 2) }} <span class="text-xl">DZD</span></span>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="errorMessage" x-text="errorMessage" class="mb-6 p-4 bg-red-50 text-red-700 font-bold text-sm rounded-xl border border-red-200" style="display: none;"></div>

                    <button @click="submitOrder" :disabled="loading" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-black py-4 px-4 rounded-2xl shadow-xl shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 hover:scale-[1.02]">
                        <span x-show="!loading" class="text-lg">Confirmer la Commande</span>
                        <span x-show="loading" class="flex items-center gap-3 text-lg">
                            <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Traitement...
                        </span>
                    </button>
                    
                    <p class="mt-6 text-[10px] text-center text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest leading-relaxed">
                        En confirmant votre commande, vous acceptez nos <a href="#" class="text-indigo-500 hover:underline">CGV</a> et notre <a href="#" class="text-indigo-500 hover:underline">Politique de Confidentialité</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.02);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.2);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.4);
        }
    </style>

    <!-- Script for handling the checkout simulation -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutForm', () => ({
                sameAsShipping: true,
                payment_method: 'cod',
                loading: false,
                errorMessage: '',
                shipping: {
                    first_name: '{{ Auth::user()->name }}',
                    last_name: 'Doe',
                    address_line1: '123 Avenue du Bien-être',
                    address_line2: '',
                    city: 'Paris',
                    postal_code: '75001',
                    country: 'FR'
                },
                billing: {
                    first_name: '',
                    last_name: '',
                    address_line1: '',
                    address_line2: '',
                    city: '',
                    postal_code: '',
                    country: ''
                },

                async submitOrder() {
                    this.loading = true;
                    this.errorMessage = '';
                    
                    const billingData = this.sameAsShipping ? this.shipping : this.billing;

                    try {
                        const response = await fetch('{{ route("checkout.initiate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                shipping: this.shipping,
                                billing: billingData,
                                payment_method: this.payment_method
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || data.error || 'Validation échouée. Veuillez vérifier vos informations.');
                        }

                        // Simulation of payment success, redirect to success page
                        window.location.href = `/checkout/success/${data.order_id}`;

                    } catch (error) {
                        this.errorMessage = error.message;
                        this.loading = false;
                    }
                }
            }))
        })
    </script>
</x-app-layout>
