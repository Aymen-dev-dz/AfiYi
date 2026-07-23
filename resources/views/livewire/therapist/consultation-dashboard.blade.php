<div wire:poll.10s>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Consultations') }}
            </h2>
            
            {{-- Notification Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    
                    @php
                        $notifCount = $activeCalls->count() + $newReservations->count();
                    @endphp
                    @if($notifCount > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[9px] font-black leading-none text-white bg-red-600 rounded-full animate-bounce">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>
                
                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-3 z-50 space-y-2 text-xs" style="display: none;">
                    <div class="px-4 py-1 border-b border-gray-150 dark:border-gray-700 pb-2">
                        <p class="font-black text-gray-900 dark:text-white uppercase tracking-wider text-[10px]">Centre de Notifications</p>
                    </div>
                    
                    <div class="max-h-60 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        @if($activeCalls->isNotEmpty())
                            @foreach($activeCalls as $call)
                                <div class="px-4 py-3 bg-red-50/40 dark:bg-red-950/10 flex flex-col gap-1.5 relative group">
                                    <button wire:click="dismissNotification({{ $call->id }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-[10px] font-black p-0.5" title="Masquer">✕</button>
                                    <div class="flex items-center gap-2 pr-4">
                                        <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                                        <p class="font-extrabold text-red-700 dark:text-red-400">Appel en cours !</p>
                                    </div>
                                    <p class="text-[11px] text-slate-550 dark:text-slate-450 pr-4">Le patient <strong class="text-slate-800 dark:text-slate-200">{{ $call->patient->name }}</strong> a rejoint la salle virtuelle.</p>
                                    <a href="{{ route('teletherapy.room', $call->id) }}" class="mt-1 px-3 py-1.5 bg-red-650 hover:bg-red-700 text-white rounded-lg text-center font-bold text-[10px] block transition">
                                        Rejoindre l'appel
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($newReservations->isNotEmpty())
                            @foreach($newReservations as $res)
                                <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/40 flex flex-col gap-1 relative group">
                                    <button wire:click="dismissNotification({{ $res->id }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-[10px] font-black p-0.5" title="Masquer">✕</button>
                                    <div class="flex items-center gap-1.5 pr-4">
                                        <span class="text-xs">📅</span>
                                        <p class="font-bold text-gray-800 dark:text-gray-200">Nouveau rendez-vous !</p>
                                    </div>
                                    <p class="text-[11px] text-slate-550 dark:text-slate-450 pr-4">Réservé par <strong>{{ $res->patient->name }}</strong> pour le {{ $res->scheduled_at->format('d/m H:i') }}.</p>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($activeCalls->isEmpty() && $newReservations->isEmpty())
                            <p class="text-center text-slate-400 py-6">Aucune nouvelle notification.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-8 relative">
        <!-- Ambient Glows -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-[100px] rounded-full pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-500/10 dark:bg-green-500/5 blur-[100px] rounded-full pointer-events-none z-0"></div>

        {{-- ── STATS CARDS ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 relative z-10">

            {{-- Today --}}
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[1.5rem] shadow-xl border border-white/50 dark:border-white/5 p-5 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-3 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-white shadow-lg shadow-indigo-500/30 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Today</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $todayCount }}</p>
                </div>
            </div>

            {{-- Upcoming --}}
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[1.5rem] shadow-xl border border-white/50 dark:border-white/5 p-5 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-3 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-500/30 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Upcoming</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $upcoming->total() }}</p>
                </div>
            </div>

            {{-- Completed --}}
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[1.5rem] shadow-xl border border-white/50 dark:border-white/5 p-5 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-500/30 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Completed</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $past->total() }}</p>
                </div>
            </div>

            {{-- Month Revenue --}}
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[1.5rem] shadow-xl border border-white/50 dark:border-white/5 p-5 flex items-center space-x-4 transition hover:scale-[1.02]">
                <div class="p-3 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white shadow-lg shadow-orange-500/30 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">This Month</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">DZD{{ number_format($monthRevenue, 0) }}</p>
                </div>
            </div>

            {{-- Wallet / Withdraw --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[1.5rem] shadow-xl p-5 flex items-center justify-between col-span-1 sm:col-span-2 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-48 h-48 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="p-3 rounded-full bg-white/20 text-white shrink-0 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-indigo-100 uppercase tracking-wide">Solde Disponible</p>
                        <p class="text-2xl font-black text-white">DZD{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
                <button class="relative z-10 px-5 py-2.5 bg-white text-indigo-700 hover:bg-gray-50 font-extrabold rounded-xl shadow-lg transition transform active:scale-95 whitespace-nowrap text-sm">
                    Demander retrait
                </button>
            </div>
        </div>

        {{-- ── FLASH MESSAGES ──────────────────────────────────────────────── --}}
        @if(session()->has('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── ACTIVE CALL BANNERS (Patient connecté) ─────────────────────── --}}
        @if($activeCalls->isNotEmpty())
            @foreach($activeCalls as $call)
                <div class="relative bg-gradient-to-r from-red-600 to-rose-600 rounded-2xl p-5 shadow-xl flex items-center justify-between gap-4 overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background: repeating-linear-gradient(45deg, #fff, #fff 2px, transparent 2px, transparent 12px);"></div>
                    <button wire:click="dismissNotification({{ $call->id }})" class="absolute top-2 right-2 text-white/60 hover:text-white text-xs font-black p-1" title="Masquer">✕</button>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center animate-pulse">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white">🔴 Patient connecté — Séance en attente !</h3>
                            <p class="text-xs text-red-100 mt-0.5"><strong>{{ $call->patient->name }}</strong> est dans la salle et attend votre arrivée.</p>
                        </div>
                    </div>
                    <a href="{{ route('teletherapy.room', $call->id) }}" class="relative z-10 flex items-center gap-2 px-5 py-2.5 bg-white text-red-600 rounded-xl text-xs font-black shadow-lg hover:bg-red-50 transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Rejoindre l'appel
                    </a>
                </div>
            @endforeach
        @endif

        {{-- ── SÉANCES D'AUJOURD'HUI ──────────────────────────────────────────── --}}
        @php
            $todaySessions = $upcoming->getCollection()->filter(fn($c) =>
                $c->scheduled_at->isToday() &&
                in_array($c->status, ['confirmed','paid','in_progress'])
            );
        @endphp
        @if($todaySessions->isNotEmpty())
            <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-3xl shadow-xl border border-white/50 dark:border-white/5 overflow-hidden relative z-10">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-inner">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white">📅 Séances d'aujourd'hui</h3>
                    <span class="ml-auto text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-3 py-1.5 rounded-full font-bold shadow-sm">{{ $todaySessions->count() }} séance(s)</span>
                </div>
                <div class="divide-y divide-gray-100/50 dark:divide-gray-700/50">
                    @foreach($todaySessions as $session)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-black text-sm">
                                {{ substr($session->patient->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $session->patient->name ?? '—' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $session->scheduled_at->format('H:i') }} · {{ $session->duration_minutes }} min · {{ ucfirst($session->type ?? 'vidéo') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($session->started_at)
                                <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>Patient connecté
                                </span>
                            @endif
                            <a href="{{ route('teletherapy.room', $session->id) }}"
                               class="flex items-center gap-1.5 px-4 py-2 {{ $session->started_at ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-black rounded-xl transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                {{ $session->started_at ? 'Rejoindre' : 'Lancer l\'appel' }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif


        {{-- ── TABS ─────────────────────────────────────────────────────────── --}}
        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl rounded-3xl shadow-xl border border-white/50 dark:border-white/5 relative z-10 overflow-hidden">
            {{-- Tab Headers --}}
            <div class="border-b border-gray-200/50 dark:border-gray-700/50 px-6">
                <nav class="flex space-x-6 -mb-px" aria-label="Tabs">
                    <button wire:click="switchTab('upcoming')"
                        class="py-4 px-1 text-sm font-medium border-b-2 transition-colors focus:outline-none
                            {{ $tab === 'upcoming'
                                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                        Upcoming Sessions
                        <span class="ml-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full px-2 py-0.5 text-xs">
                            {{ $upcoming->total() }}
                        </span>
                    </button>
                    <button wire:click="switchTab('past')"
                        class="py-4 px-1 text-sm font-medium border-b-2 transition-colors focus:outline-none
                            {{ $tab === 'past'
                                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                        Past Sessions
                        <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5 text-xs">
                            {{ $past->total() }}
                        </span>
                    </button>
                    <button wire:click="switchTab('messages')"
                        class="py-4 px-1 text-sm font-medium border-b-2 transition-colors focus:outline-none
                            {{ $tab === 'messages'
                                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                        💬 Patient Messages
                    </button>
                </nav>
            </div>

            <div class="p-6">
                {{-- ── UPCOMING TAB ──────────────────────────────────────── --}}
                @if($tab === 'upcoming')
                    @if($upcoming->isEmpty())
                        <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-lg font-medium">No upcoming sessions</p>
                            <p class="text-sm mt-1">Your schedule is clear. Enjoy your time!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap text-sm">
                                <thead>
                                    <tr class="text-left font-semibold text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                                        <th class="pb-3 pr-4">Patient</th>
                                        <th class="pb-3 pr-4">Date</th>
                                        <th class="pb-3 pr-4">Time</th>
                                        <th class="pb-3 pr-4">Duration</th>
                                        <th class="pb-3 pr-4">Type</th>
                                        <th class="pb-3 pr-4">Status</th>
                                        <th class="pb-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($upcoming as $consultation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                            <td class="py-3 pr-4">
                                                <div class="flex items-center space-                                                    <button wire:click="viewPatientProfile({{ $consultation->patient_id }})" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline text-left">
                                                        {{ $consultation->patient->name ?? '—' }}
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->scheduled_at->format('D, M d, Y') }}
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->scheduled_at->format('H:i') }}
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->duration_minutes }} min
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300 capitalize">
                                                {{ $consultation->type ?? 'video' }}
                                            </td>
                                            <td class="py-3 pr-4">
                                                @php
                                                    $statusMap = [
                                                        'confirmed'   => ['bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', 'Confirmed'],
                                                        'paid'        => ['bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200', 'Paid'],
                                                        'in_progress' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200', 'In Progress'],
                                                        'pending'     => ['bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300', 'Pending'],
                                                    ];
                                                    [$badgeClass, $label] = $statusMap[$consultation->status] ?? ['bg-gray-100 text-gray-600', ucfirst($consultation->status)];
                                                @endphp
                                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full {{ $badgeClass }}">
                                                    {{ $label }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="flex items-center space-x-2">
                                                    @if($consultation->status === 'confirmed' || $consultation->status === 'paid' || $consultation->daily_room_url)
                                                        <a href="{{ route('teletherapy.room', $consultation) }}"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $consultation->started_at ? 'bg-green-600 hover:bg-green-700 ring-2 ring-green-400/30' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-bold rounded-lg transition shadow-sm">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ $consultation->started_at ? '🟢 Patient connecté' : 'Lancer l\'appel' }}
                                                        </a>
                                                    @endif
                                                    <button x-data @click="$dispatch('openPreConsultationChat', { id: {{ $consultation->id }} })" class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-lg transition shadow-sm gap-1">
                                                        <span>💬</span> Chat
                                                    </button>
                                                    <button wire:click="openNote({{ $consultation->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-lg transition">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Notes
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $upcoming->links() }}
                        </div>
                    @endif
                @endif

                {{-- ── PAST TAB ──────────────────────────────────────────── --}}
                @if($tab === 'past')
                    @if($past->isEmpty())
                        <div class="text-center py-16 text-gray-400 dark:text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-medium">No past sessions yet</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap text-sm">
                                <thead>
                                    <tr class="text-left font-semibold text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                                        <th class="pb-3 pr-4">Patient</th>
                                        <th class="pb-3 pr-4">Date</th>
                                        <th class="pb-3 pr-4">Duration</th>
                                        <th class="pb-3 pr-4">Payment</th>
                                        <th class="pb-3 pr-4">Notes</th>
                                        <th class="pb-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($past as $consultation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                            <td class="py-3 pr-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-300 font-bold text-xs shrink-0">
                                                        {{ strtoupper(substr($consultation->patient->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <button wire:click="viewPatientProfile({{ $consultation->patient_id }})" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline text-left">
                                                        {{ $consultation->patient->name ?? '—' }}
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->scheduled_at->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->actual_duration_minutes ?? $consultation->duration_minutes }} min
                                            </td>
                                            <td class="py-3 pr-4">
                                                @if($consultation->paid_at || $consultation->is_free)
                                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $consultation->is_free ? 'Free' : 'DZD'.number_format($consultation->price, 2) }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Unpaid
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                                {{ $consultation->notes->count() }} note(s)
                                            </td>
                                            <td class="py-3">
                                                <button wire:click="openNote({{ $consultation->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-lg transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Add / View Notes
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $past->links() }}
                        </div>
                    @endif
                @endif

                {{-- ── MESSAGES TAB ──────────────────────────────────────────── --}}
                @if($tab === 'messages')
                    <div class="flex flex-col md:flex-row gap-6 bg-slate-50 dark:bg-slate-900/10 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800" style="height: 600px;">
                        
                        {{-- Left Panel: Patients/Conversations --}}
                        <div class="w-full md:w-1/3 border-r border-gray-150 dark:border-gray-800 flex flex-col bg-white dark:bg-gray-900/20">
                            <div class="p-4 border-b border-gray-150 dark:border-gray-800">
                                <h4 class="font-extrabold text-gray-800 dark:text-white text-sm">💬 Patients</h4>
                                <p class="text-[10px] text-slate-400">Sélectionnez un patient pour voir les messages.</p>
                            </div>
                            <div class="flex-1 overflow-y-auto divide-y divide-gray-100/60 dark:divide-gray-800/40">
                                @forelse($conversations as $conv)
                                    @php
                                        $isSelected = $conv->id === $selectedConsultationId;
                                        $unreadCount = $conv->messages()
                                            ->where('sender_id', '!=', Auth::id())
                                            ->whereNull('read_at')
                                            ->count();
                                    @endphp
                                    <button wire:click="selectConsultation({{ $conv->id }})" class="w-full text-left p-4 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition flex items-center justify-between {{ $isSelected ? 'bg-indigo-50/50 dark:bg-indigo-950/20 border-l-4 border-indigo-500' : '' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-extrabold text-xs">
                                                {{ strtoupper(substr($conv->patient->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-white text-xs">{{ $conv->patient->name ?? '—' }}</p>
                                                <p class="text-[9px] text-slate-400 font-mono">Réf: {{ $conv->reference }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($unreadCount > 0)
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full bg-indigo-600 text-[9px] font-black text-white">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </button>
                                @empty
                                    <div class="p-8 text-center text-xs text-slate-400">
                                        Aucun patient disponible pour le moment.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Right Panel: Chat Messages --}}
                        <div class="flex-1 flex flex-col bg-white dark:bg-gray-950/10">
                            @if($selectedConsultationId)
                                @php
                                    $activeConv = $conversations->firstWhere('id', $selectedConsultationId);
                                @endphp
                                <div class="px-6 py-4 border-b border-gray-150 dark:border-gray-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/15">
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Chat avec {{ $activeConv->patient->name ?? 'Patient' }}</h4>
                                        <p class="text-[10px] text-slate-400">Consultation prévue le {{ $activeConv->scheduled_at ? $activeConv->scheduled_at->format('d/m/Y H:i') : 'Non planifiée' }}</p>
                                    </div>
                                </div>

                                {{-- Messages Container --}}
                                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-900/5" id="therapist-inline-chat-messages" wire:poll.5s>
                                    @forelse($chatMessages as $msg)
                                        @php
                                            $isMe = $msg->sender_id === Auth::id();
                                        @endphp
                                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                            <div class="max-w-[75%] {{ $isMe ? 'bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-r-2xl rounded-tl-2xl border border-gray-200/40 dark:border-gray-700/40' }} p-3 shadow-sm">
                                                <p class="text-xs leading-relaxed">{{ $msg->message }}</p>
                                                <span class="text-[9px] opacity-60 block mt-1 {{ $isMe ? 'text-right text-indigo-200' : 'text-left text-slate-400' }}">
                                                    {{ $msg->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2 py-20">
                                            <span class="text-3xl">👋</span>
                                            <p class="text-[11px] font-medium text-center">Aucun message pour le moment.<br>Écrivez ci-dessous pour démarrer l'échange.</p>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Message Input --}}
                                <div class="p-4 border-t border-gray-150 dark:border-gray-800 bg-white dark:bg-gray-900/35">
                                    <form wire:submit.prevent="sendMessageText" class="flex gap-2">
                                        <input type="text" wire:model.defer="newMessageText" placeholder="Écrivez votre message..." class="flex-1 bg-slate-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-xs rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white dark:placeholder-gray-400" required>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-5 py-3 shadow-sm font-bold text-xs flex items-center justify-center transition" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="sendMessageText">Envoyer</span>
                                            <span wire:loading wire:target="sendMessageText">...</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 space-y-2 py-20">
                                    <span class="text-4xl">💬</span>
                                    <p class="text-xs font-bold">Sélectionnez un patient sur la gauche pour afficher les messages.</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Inline Chat auto-scroll script -->
                    <script>
                        document.addEventListener('livewire:initialized', () => {
                            const scrollInlineChat = () => {
                                const container = document.getElementById('therapist-inline-chat-messages');
                                if (container) container.scrollTop = container.scrollHeight;
                            };
                            
                            // Scroll on load or tab switch
                            setTimeout(scrollInlineChat, 200);
                            
                            // Scroll on morph update
                            Livewire.hook('morph.updated', ({ component }) => {
                                if (component.name === 'therapist.consultation-dashboard') {
                                    scrollInlineChat();
                                }
                            });
                        });
                    </script>
                @endif
            </div>
        </div>

    </div>

    {{-- ── PATIENT PROFILE MODAL ──────────────────────────────────────────── --}}
    @if($viewingPatientId !== null && $patientData)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-data x-on:keydown.escape.window="$wire.closePatientProfile()">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg mx-4 flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Patient Profile</h3>
                    <button wire:click="closePatientProfile" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <h4 class="text-md font-bold text-gray-800 dark:text-white">{{ $patientData['name'] }}</h4>
                        <p class="text-sm text-gray-500">Joined {{ $patientData['joined_at'] }}</p>
                    </div>

                    <hr class="dark:border-gray-700">

                    @if($patientData['shares_mood'])
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="text-sm font-bold text-blue-800 dark:text-blue-300">Suivi et Statistiques du Patient</h5>
                            </div>
                            
                            <!-- Averages Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                                <div class="bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 text-center">
                                    <span class="block text-[9px] uppercase font-bold text-slate-400">😊 Humeur Moy.</span>
                                    <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">{{ $patientData['avg_mood'] ?? '—' }}/10</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 text-center">
                                    <span class="block text-[9px] uppercase font-bold text-slate-400">😰 Stress Moy.</span>
                                    <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">{{ $patientData['avg_stress'] ?? '—' }}/10</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 text-center">
                                    <span class="block text-[9px] uppercase font-bold text-slate-400">😴 Sommeil Moy.</span>
                                    <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">{{ $patientData['avg_sleep'] ?? '—' }}/10</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 text-center">
                                    <span class="block text-[9px] uppercase font-bold text-slate-400">⚡ Énergie Moy.</span>
                                    <span class="block text-sm font-black text-gray-800 dark:text-white mt-1">{{ $patientData['avg_energy'] ?? '—' }}/10</span>
                                </div>
                            </div>

                            @if(count($patientData['recent_moods']) > 0)
                                <div class="max-h-[220px] overflow-y-auto space-y-2 pr-1">
                                    @foreach($patientData['recent_moods'] as $mood)
                                        <div class="text-xs bg-white dark:bg-gray-800 p-2.5 rounded border border-gray-100 dark:border-gray-700">
                                            <div class="flex items-center justify-between font-bold text-gray-800 dark:text-white mb-1">
                                                <span>{{ $mood->created_at->format('d M H:i') }}</span>
                                                <span class="text-indigo-600 dark:text-indigo-400">Score: {{ $mood->mood_score }}/10</span>
                                            </div>
                                            <div class="grid grid-cols-3 gap-1 text-[10px] text-gray-500 mb-1">
                                                <span>Stress: <strong>{{ $mood->stress_level ?? '—' }}/10</strong></span>
                                                <span>Sommeil: <strong>{{ $mood->sleep_quality ?? '—' }}/10</strong></span>
                                                <span>Énergie: <strong>{{ $mood->energy_level ?? '—' }}/10</strong></span>
                                            </div>
                                            @if($mood->note)
                                                <div class="text-[10px] text-slate-400 bg-gray-50 dark:bg-gray-900/50 p-1.5 rounded italic">
                                                    {{ $mood->note }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">Aucune donnée d'humeur enregistrée pour le moment.</p>
                            @endif
                        </div>
                    @endif

                    <!-- Recent Purchases -->
                    <div class="mt-4 bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl border border-purple-100 dark:border-purple-800">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-sm font-bold text-purple-800 dark:text-purple-300">Wellness Purchases</h5>
                        </div>
                        
                        @if(count($patientData['recent_orders']) > 0)
                            <div class="space-y-3">
                                @foreach($patientData['recent_orders'] as $order)
                                    <div class="text-sm bg-white dark:bg-gray-800 p-3 rounded shadow-sm">
                                        <div class="flex justify-between text-xs text-gray-500 mb-2 border-b dark:border-gray-700 pb-1">
                                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                                            <span class="capitalize">{{ $order->status }}</span>
                                        </div>
                                        <ul class="space-y-1">
                                            @foreach($order->items as $item)
                                                <li class="flex items-center text-gray-700 dark:text-gray-300 text-xs">
                                                    <span class="mr-2 text-purple-500">•</span>
                                                    {{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No purchases found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── CLINICAL NOTES MODAL ──────────────────────────────────────────── --}}
    @if($selectedNoteConsultationId !== null)
        @php $modalConsultation = $tab === 'upcoming'
            ? $upcoming->firstWhere('id', $selectedNoteConsultationId)
            : $past->firstWhere('id', $selectedNoteConsultationId);
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-data x-on:keydown.escape.window="$wire.selectedNoteConsultationId = null">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Clinical Notes</h3>
                        @if($modalConsultation)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $modalConsultation->patient->name ?? '—' }}
                                · {{ $modalConsultation->scheduled_at->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                    <button wire:click="$set('selectedNoteConsultationId', null)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Existing Notes --}}
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
                    @if($modalConsultation && $modalConsultation->notes->isNotEmpty())
                        @foreach($modalConsultation->notes->sortByDesc('created_at') as $note)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide
                                        {{ $note->visibility === 'therapist_only'
                                            ? 'text-purple-600 dark:text-purple-400'
                                            : 'text-green-600 dark:text-green-400' }}">
                                        {{ $note->visibility === 'therapist_only' ? '🔒 Therapist Only' : '👁 Shared with Patient' }}
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                                    {{ $note->content_encrypted }}
                                </p>
                                @if($note->tags)
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($note->tags as $tag)
                                            <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-xs">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">No notes yet for this session.</p>
                    @endif
                </div>

                {{-- Add New Note Form --}}
                <div class="px-6 py-4 border-t dark:border-gray-700 space-y-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Add a Note</h4>

                    @error('noteContent')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <textarea wire:model="noteContent"
                        rows="4"
                        placeholder="Write your clinical note here (minimum 10 characters)…"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>

                    <div class="flex items-center justify-between">
                        <select wire:model="noteVisibility"
                            class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="therapist_only">🔒 Therapist Only</option>
                            <option value="shared_with_patient">👁 Share with Patient</option>
                        </select>

                        <div class="flex space-x-2">
                            <button wire:click="$set('selectedNoteConsultationId', null)"
                                class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition">
                                Cancel
                            </button>
                            <button wire:click="saveNote"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="saveNote">Save Note</span>
                                <span wire:loading wire:target="saveNote">Saving…</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Note saved toast --}}
    <div x-data="{ show: false }"
        x-on:note-saved.window="show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-xl">
        ✓ Note saved successfully
    </div>
    
    <!-- PreConsultation Chat Modal -->
    <livewire:pre-consultation-chat :key="'pre-chat-modal-therapist'" />
</div>
