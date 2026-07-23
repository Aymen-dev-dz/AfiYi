<div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-3xl shadow-xl shadow-indigo-900/5 border border-white/50 dark:border-white/5 p-6 mb-8">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            Évolution de mon bien-être
        </h3>
        
        <div class="flex items-center gap-3 bg-white/40 dark:bg-gray-800/40 p-1.5 rounded-xl border border-white/50 dark:border-gray-700/50 shadow-sm backdrop-blur-md">
            <button wire:click="setPeriod('week')" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all {{ $period === 'week' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">Semaine</button>
            <button wire:click="setPeriod('month')" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all {{ $period === 'month' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">Mois</button>
            <button wire:click="setPeriod('all')" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all {{ $period === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">Tout</button>
        </div>
        
        <button wire:click="analyzeTrends" wire:loading.attr="disabled" class="relative group px-6 py-2.5 font-bold rounded-xl text-white text-xs shadow-xl shadow-purple-500/30 overflow-hidden transition-all hover:-translate-y-0.5 active:translate-y-0">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-500 opacity-90 group-hover:opacity-100 transition-opacity"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0)_0%,rgba(255,255,255,0.2)_50%,rgba(255,255,255,0)_100%)] w-[200%] -translate-x-[100%] group-hover:animate-[shimmer_2s_infinite]"></div>
            <div class="relative flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="analyzeTrends" class="flex items-center gap-1.5">
                    <span class="group-hover:scale-125 transition-transform duration-300">✨</span> Analyser mes tendances
                </span>
                <span wire:loading wire:target="analyzeTrends" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Analyse en cours...
                </span>
            </div>
        </button>
    </div>

    @if($aiAnalysis)
        <div class="mb-8 p-6 bg-gradient-to-br from-indigo-50/80 to-purple-50/80 dark:from-indigo-900/30 dark:to-purple-900/30 border border-white/60 dark:border-indigo-800/50 rounded-2xl relative overflow-hidden shadow-sm backdrop-blur-xl">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-purple-500/20 rounded-full blur-3xl"></div>
            <h4 class="text-sm font-black text-indigo-900 dark:text-indigo-200 mb-3 flex items-center gap-2 relative z-10">
                <span class="text-xl">🤖</span> L'avis de l'IA bienveillante :
            </h4>
            <p class="text-xs text-indigo-800 dark:text-indigo-300 leading-relaxed whitespace-pre-wrap relative z-10">{{ $aiAnalysis }}</p>
        </div>
    @endif

    <div class="relative h-64 w-full" x-data="{ 
            chartInstance: null,
            initChart(data) {
                if(!data || data.length === 0) return;
                
                const ctx = document.getElementById('moodChart');
                if(!ctx) return;
                
                if(this.chartInstance) {
                    this.chartInstance.data.labels = data.map(item => item.date);
                    this.chartInstance.data.datasets[0].data = data.map(item => item.score);
                    this.chartInstance.update();
                    return;
                }

                // Create gradient for chart fill
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                const chartData = {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Score de Bien-être',
                        data: data.map(item => item.score),
                        borderColor: '#6366f1',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                };

                const config = {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { min: 0, max: 100, ticks: { stepSize: 20, color: 'rgba(156, 163, 175, 0.8)', font: { size: 10, weight: 'bold' } }, grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false } },
                            x: { ticks: { color: 'rgba(156, 163, 175, 0.8)', font: { size: 10, weight: 'bold' } }, grid: { display: false, drawBorder: false } }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.95)', padding: 12, cornerRadius: 12, displayColors: false,
                                titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 14, weight: 'bold' },
                                callbacks: { label: function(context) { return 'Score : ' + context.parsed.y + '%'; } }
                            }
                        }
                    }
                };
                
                this.chartInstance = new Chart(ctx, config);
            }
        }" 
        x-init="initChart(@js($chartData))"
        @chartDataUpdated.window="initChart($event.detail.data)"
    >
        @if(empty($chartData))
            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 bg-white/40 dark:bg-gray-800/40 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <p class="text-sm font-black">Pas encore assez de données.</p>
                <p class="text-xs mt-1">Enregistrez votre première humeur pour voir le graphique !</p>
            </div>
        @else
            <canvas id="moodChart"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
        @endif
    </div>

    @if(!empty($chartData))
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        <!-- Stress Card -->
        <div class="relative overflow-hidden bg-white/50 dark:bg-gray-800/50 p-5 rounded-3xl border border-white/60 dark:border-white/5 shadow-lg shadow-gray-200/50 dark:shadow-none group hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all"></div>
            <div class="flex items-center gap-4 relative z-10 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-red-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform">
                    😰
                </div>
                <div class="flex-1 text-left">
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Stress Moyen</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ $avgStress }}%</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700/50 rounded-full h-2 overflow-hidden relative z-10">
                <div class="bg-gradient-to-r from-rose-400 to-red-500 h-2 rounded-full shadow-[0_0_10px_rgba(244,63,94,0.5)]" style="width: {{ $avgStress }}%"></div>
            </div>
        </div>

        <!-- Sommeil Card -->
        <div class="relative overflow-hidden bg-white/50 dark:bg-gray-800/50 p-5 rounded-3xl border border-white/60 dark:border-white/5 shadow-lg shadow-gray-200/50 dark:shadow-none group hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <div class="flex items-center gap-4 relative z-10 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                    😴
                </div>
                <div class="flex-1 text-left">
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Sommeil</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ $avgSleep }}%</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700/50 rounded-full h-2 overflow-hidden relative z-10">
                <div class="bg-gradient-to-r from-blue-400 to-indigo-600 h-2 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]" style="width: {{ $avgSleep }}%"></div>
            </div>
        </div>

        <!-- Énergie Card -->
        <div class="relative overflow-hidden bg-white/50 dark:bg-gray-800/50 p-5 rounded-3xl border border-white/60 dark:border-white/5 shadow-lg shadow-gray-200/50 dark:shadow-none group hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="flex items-center gap-4 relative z-10 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                    ⚡
                </div>
                <div class="flex-1 text-left">
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Énergie</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ $avgEnergy }}%</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700/50 rounded-full h-2 overflow-hidden relative z-10">
                <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full shadow-[0_0_10px_rgba(245,158,11,0.5)]" style="width: {{ $avgEnergy }}%"></div>
            </div>
        </div>

        <!-- Social Card -->
        <div class="relative overflow-hidden bg-white/50 dark:bg-gray-800/50 p-5 rounded-3xl border border-white/60 dark:border-white/5 shadow-lg shadow-gray-200/50 dark:shadow-none group hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex items-center gap-4 relative z-10 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    👥
                </div>
                <div class="flex-1 text-left">
                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Social</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ $avgSocial }}%</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700/50 rounded-full h-2 overflow-hidden relative z-10">
                <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-2 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ $avgSocial }}%"></div>
            </div>
        </div>
    </div>
    @endif
</div>
