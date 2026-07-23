<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Analytics Dashboard') }}
            </h2>
            
            <div class="flex items-center space-x-4">
                <select wire:model.live="period" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="daily">Daily (Last 30 days)</option>
                    <option value="weekly">Weekly (Last 12 weeks)</option>
                    <option value="monthly">Monthly (Last 12 months)</option>
                </select>

                <button wire:click="exportData" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-8 max-w-7xl mx-auto relative">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-violet-500/10 dark:bg-violet-500/5 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        {{-- ── KPIs ───────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
            
            <!-- Users -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Utilisateurs</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $kpis['total_users'] }}</p>
                </div>
            </div>

            <!-- Therapists -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-white shadow-lg shadow-teal-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Thérapeutes Actifs</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $kpis['total_therapists'] }}</p>
                </div>
            </div>

            <!-- Consultations -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Consultations</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $kpis['total_consultations'] }}</p>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Revenus</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($kpis['total_revenue'], 2) }} DZD</p>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Commandes</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $kpis['total_orders'] }}</p>
                </div>
            </div>

            <!-- Products Sold -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2rem] shadow-xl border border-white/50 dark:border-white/5 p-6 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-4 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 text-white shadow-lg shadow-pink-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Produits Vendus</h3>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $kpis['total_products_sold'] }}</p>
                </div>
            </div>
        </div>

        {{-- ── CENTRE DE MODÉRATION ── --}}
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8 relative z-10 space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-4">
                <span class="text-2xl">🛡️</span>
                <div>
                    <h3 class="text-lg font-black text-gray-850 dark:text-white">Centre de Modération (Alertes & Signalements)</h3>
                    <p class="text-xs text-slate-400">Signalements émis par les utilisateurs ou l'IA en raison de comportements inappropriés ou de détresse psychologique.</p>
                </div>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($reports as $report)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 first:pt-0 last:pb-0">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-300 text-[10px] font-black uppercase rounded">
                                    {{ $report->reported->is_active ? 'Actif' : 'Banni/Inactif' }}
                                </span>
                                <span class="text-xs text-slate-400">Signalé par : <strong class="text-slate-700 dark:text-slate-350">{{ $report->reporter->name }}</strong></span>
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-805 dark:text-white">
                                Utilisateur ciblé : <span class="text-indigo-600 dark:text-indigo-400">{{ $report->reported->name }}</span> ({{ $report->reported->email }})
                            </h4>
                            <p class="text-xs text-slate-400 bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 mt-2 leading-relaxed">
                                <strong>Motif :</strong> {{ $report->reason }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($report->reported->is_active)
                                <button wire:click="banUser({{ $report->reported_id }})" wire:confirm="Êtes-vous sûr de vouloir bannir cet utilisateur ?" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                    Bannir
                                </button>
                            @endif
                            <button wire:click="resolveReport({{ $report->id }})" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-750 dark:text-gray-300 rounded-xl text-xs font-bold transition">
                                Résoudre
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Aucun signalement en attente. Votre communauté est sereine ! ✨</p>
                @endforelse
            </div>
        </div>

        {{-- ── CHARTS ─────────────────────────────────────────────────────── --}}
        
        <!-- Revenue & Consultations Chart -->
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8 relative z-10">
            <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-4">Tendances Revenus & Consultations</h3>
            <div id="chart-revenue" style="height: 350px;"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative z-10">
            <!-- Mood Trends Chart -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-4">Humeur Globale de la Plateforme</h3>
                <div id="chart-mood" style="height: 300px;"></div>
            </div>

            <!-- AI Chat Usage Chart -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8">
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-4">Usage du Chat IA Bien-être</h3>
                <div id="chart-ai" style="height: 300px;"></div>
            </div>

            <!-- Advanced platform stats -->
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8 flex flex-col justify-between">
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-4">Indicateurs de Fidélisation & Échange</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50/50 dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-100/50 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Taux de Retour</span>
                        <span class="text-4xl font-black text-gray-800 dark:text-white mt-2 block">{{ $advancedStats['return_rate_percent'] }}%</span>
                        <p class="text-[10px] text-slate-400 mt-2 leading-normal">Pourcentage d'utilisateurs achetant plus d'un produit bien-être.</p>
                    </div>
                    <div class="bg-slate-50/50 dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-100/50 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Durée Discussion</span>
                        <span class="text-4xl font-black text-gray-800 dark:text-white mt-2 block">{{ floor($advancedStats['avg_chat_duration_seconds'] / 60) }} m</span>
                        <p class="text-[10px] text-slate-400 mt-2 leading-normal">Durée moyenne des sessions de discussion anonymes fermées.</p>
                    </div>
                </div>
                <div class="bg-indigo-50/30 dark:bg-indigo-900/20 p-6 rounded-3xl border border-indigo-100/30 dark:border-indigo-800/30 mt-4 flex items-center justify-between shadow-inner">
                    <div>
                        <span class="text-xs font-bold text-indigo-800 dark:text-indigo-400 block uppercase tracking-widest">Besoin Émotionnel Principal</span>
                        <span class="text-lg font-black text-gray-800 dark:text-white mt-1 block">Stress & Anxiété</span>
                    </div>
                    <span class="text-4xl">😰</span>
                </div>
            </div>
        </div>

        <!-- Wellness indicators Heatmap -->
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl border border-white/50 dark:border-white/5 p-8 relative z-10">
            <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 mb-2">Carte d'Intensité Émotionnelle Globale</h3>
            <p class="text-sm font-medium text-slate-400 mb-6">Moyenne des indicateurs anonymisés agrégés par semaine (Base de données réelle).</p>
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs font-semibold">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-700/50">
                            <th class="pb-3 text-slate-400 uppercase tracking-widest">Indicateur</th>
                            @foreach($heatmapData['weeks'] as $week)
                                <th class="pb-3 text-slate-400 uppercase tracking-widest text-center">{{ $week }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50 dark:divide-slate-700/50">
                        @foreach($heatmapData['categories'] as $cat)
                            <tr>
                                <td class="py-4 text-gray-800 dark:text-gray-200 font-bold text-sm">{{ $cat }}</td>
                                @foreach($heatmapData['values'][$cat] as $val)
                                    <td class="py-4 text-center">
                                        <div class="inline-block px-4 py-2 rounded-xl text-xs font-black" 
                                             style="background-color: {{ $val > 80 ? 'rgba(239, 68, 68, 0.15)' : ($val > 60 ? 'rgba(245, 158, 11, 0.15)' : 'rgba(16, 185, 129, 0.15)') }};
                                                    color: {{ $val > 80 ? '#ef4444' : ($val > 60 ? '#f59e0b' : '#10b981') }};">
                                            {{ $val }}%
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
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

    <!-- ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let revenueChart, moodChart, aiChart;

            const renderCharts = () => {
                const dates = @this.chartDates;
                const revenue = @this.chartRevenue;
                const consultations = @this.chartConsultations;
                const mood = @this.chartMood;
                const ai = @this.chartAi;

                const commonOptions = {
                    chart: { toolbar: { show: false }, fontFamily: 'inherit', background: 'transparent' },
                    theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                    xaxis: { categories: dates, tooltip: { enabled: false } },
                };

                // Revenue & Consultations
                if (revenueChart) revenueChart.destroy();
                revenueChart = new ApexCharts(document.querySelector("#chart-revenue"), {
                    ...commonOptions,
                    chart: { ...commonOptions.chart, type: 'line', height: 350 },
                    series: [
                        { name: 'Revenue (DZD)', type: 'area', data: revenue },
                        { name: 'Consultations', type: 'line', data: consultations }
                    ],
                    colors: ['#10b981', '#8b5cf6'],
                    stroke: { curve: 'smooth', width: [2, 3] },
                    fill: { type: ['gradient', 'solid'], gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
                    yaxis: [
                        { title: { text: 'Revenue (DZD)' }, labels: { formatter: (val) => val.toFixed(0) } },
                        { opposite: true, title: { text: 'Consultations' }, labels: { formatter: (val) => val.toFixed(0) } }
                    ],
                });
                revenueChart.render();

                // Mood Trends
                if (moodChart) moodChart.destroy();
                moodChart = new ApexCharts(document.querySelector("#chart-mood"), {
                    ...commonOptions,
                    chart: { ...commonOptions.chart, type: 'area', height: 300 },
                    series: [{ name: 'Average Mood Score', data: mood }],
                    colors: ['#f59e0b'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
                    yaxis: { min: 0, max: 10, tickAmount: 5 },
                });
                moodChart.render();

                // AI Chat Usage
                if (aiChart) aiChart.destroy();
                aiChart = new ApexCharts(document.querySelector("#chart-ai"), {
                    ...commonOptions,
                    chart: { ...commonOptions.chart, type: 'bar', height: 300 },
                    series: [{ name: 'Chat Interactions', data: ai }],
                    colors: ['#3b82f6'],
                    plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
                    yaxis: { labels: { formatter: (val) => val.toFixed(0) } },
                });
                aiChart.render();
            };

            renderCharts();

            Livewire.on('update-charts', () => {
                renderCharts();
            });
        });
    </script>
</div>
