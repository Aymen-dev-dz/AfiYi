<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        How are you feeling today?
    </h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submitMood" class="space-y-6">
        <!-- Mood Score -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mood Score (1-5)</label>
            <input type="range" wire:model="mood_score" min="1" max="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-purple-600">
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>Terrible</span>
                <span>Bad</span>
                <span>Neutral</span>
                <span>Good</span>
                <span>Excellent</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Stress Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stress (1-10)</label>
                <input type="range" wire:model="stress_level" min="1" max="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-red-500">
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Low</span>
                    <span>High</span>
                </div>
            </div>

            <!-- Sleep Quality -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sleep (1-10)</label>
                <input type="range" wire:model="sleep_quality" min="1" max="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-blue-500">
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Poor</span>
                    <span>Great</span>
                </div>
            </div>

            <!-- Energy Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Energy (1-10)</label>
                <input type="range" wire:model="energy_level" min="1" max="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-yellow-500">
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Exhausted</span>
                    <span>Full</span>
                </div>
            </div>
        </div>

        <!-- Emotion Selector -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Emotion</label>
            <select wire:model="emotion" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <option value="happy">Happy</option>
                <option value="calm">Calm</option>
                <option value="neutral">Neutral</option>
                <option value="anxious">Anxious</option>
                <option value="sad">Sad</option>
                <option value="angry">Angry</option>
                <option value="tired">Tired</option>
            </select>
        </div>

        <!-- Journal Note -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Journal Note (Optional)</label>
            <textarea wire:model="note" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="What's making you feel this way?"></textarea>
        </div>

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm">
            Log Mood
        </button>
    </form>

    @if(count($moodHistory) > 0)
        <div class="mt-8 border-t border-gray-100 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Your Recent Trends</h3>
            
            <!-- ApexChart Container -->
            <div id="moodChart" class="w-full h-64" wire:ignore></div>
        </div>

        <!-- Initialize ApexChart -->
        <script>
            document.addEventListener('livewire:initialized', () => {
                const chartData = {!! $chartData !!};
                
                if (chartData.length > 0) {
                    const options = {
                        series: [
                            { name: 'Mood (x2)', data: chartData.map(d => ({ x: d.x, y: d.mood })) },
                            { name: 'Stress', data: chartData.map(d => ({ x: d.x, y: d.stress })) },
                            { name: 'Sleep', data: chartData.map(d => ({ x: d.x, y: d.sleep })) },
                            { name: 'Energy', data: chartData.map(d => ({ x: d.x, y: d.energy })) }
                        ],
                        chart: {
                            type: 'line',
                            height: 250,
                            toolbar: { show: false },
                            background: 'transparent'
                        },
                        colors: ['#9333ea', '#ef4444', '#3b82f6', '#eab308'],
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            type: 'category',
                            labels: {
                                style: { colors: '#6b7280', fontFamily: 'inherit' }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            min: 1,
                            max: 10,
                            tickAmount: 4,
                            labels: {
                                style: { colors: '#6b7280', fontFamily: 'inherit' }
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            labels: { colors: '#6b7280' }
                        },
                        grid: {
                            borderColor: '#e5e7eb',
                            strokeDashArray: 4,
                            yaxis: { lines: { show: true } }
                        },
                        theme: {
                            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#moodChart"), options);
                    chart.render();
                }
            });
        </script>
    @endif
</div>
