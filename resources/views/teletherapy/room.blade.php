<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Séance — {{ Auth::id() === $consultation->patient_id ? $consultation->therapistProfile->user->name : $consultation->patient->name }} · AF IYI</title>
    <meta name="robots" content="noindex,nofollow">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        body { background: #0a0a0f; margin: 0; overflow-y: auto; overflow-x: hidden; }

        /* Floating controls bar */
        .controls-bar {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 15, 20, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 999px;
            padding: 10px 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.6);
        }

        .ctrl-btn {
            width: 48px; height: 48px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .2s;
        }
        .ctrl-btn:hover { transform: scale(1.1); }
        .ctrl-btn.active   { background: rgba(255,255,255,0.12); color: #fff; }
        .ctrl-btn.muted    { background: rgba(239,68,68,0.25); color: #ef4444; }
        .ctrl-btn.end-call { background: #ef4444; color: #fff; width: 60px; height: 60px; }
        .ctrl-btn.end-call:hover { background: #dc2626; }

        /* Header overlay */
        .session-header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 90;
            background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, transparent 100%);
            padding: 16px 24px;
            display: flex; align-items: center; justify-content: space-between;
        }

        /* Sidebar */
        .sidebar-panel {
            position: fixed; right: 0; top: 0; bottom: 0;
            width: 340px; z-index: 80;
            background: rgba(12,12,18,0.95);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255,255,255,0.06);
            transform: translateX(100%);
            transition: transform .3s ease;
        }
        .sidebar-panel.open { transform: translateX(0); }

        /* Jitsi container */
        #jitsi-container { width: 100%; height: 100vh; background: #0a0a0f; }
        #jitsi-container iframe { border: none; }

        /* Status pill */
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16,185,129,0.3);
            color: #6ee7b7; font-size: 11px; font-weight: 700;
            padding: 4px 12px; border-radius: 999px; letter-spacing: .04em;
        }
        .status-pill .dot { width: 7px; height: 7px; background: #10b981; border-radius: 50%; animation: blink 1.2s ease-in-out infinite; }
        @keyframes blink { 0%,100% { opacity:1 } 50% { opacity: .35 } }

        /* Consent card */
        .consent-card {
            max-width: 560px; margin: 10vh auto;
            background: #13131a; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px; overflow: hidden;
            box-shadow: 0 24px 80px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body class="h-full">

    @if(Auth::id() === $consultation->patient_id && !session("consent_for_{$consultation->id}"))
        {{-- ── CONSENT MODAL ──────────────────────────────────────────────── --}}
        <div class="min-h-screen flex items-start justify-center bg-[#0a0a0f] py-12 px-4 overflow-y-auto">
            <div class="consent-card w-full">
                <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-8 py-6 flex items-center gap-4">
                    <span class="text-4xl">🔒</span>
                    <div>
                        <h2 class="text-xl font-black text-white">Consentement de Téléconsultation</h2>
                        <p class="text-indigo-200 text-sm mt-0.5">Avant d'entrer dans la salle sécurisée</p>
                    </div>
                </div>

                <div class="px-8 py-7">
                    <div class="flex items-center gap-3 mb-6 bg-violet-950/40 border border-violet-800/40 rounded-xl px-4 py-3">
                        <span class="text-2xl">👨‍⚕️</span>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Votre thérapeute</p>
                            <p class="text-white font-black text-sm">{{ $consultation->therapistProfile->user->name }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Heure prévue</p>
                            <p class="text-white font-black text-sm">{{ $consultation->scheduled_at->format('d/m/Y · H:i') }}</p>
                        </div>
                    </div>

                    <h3 class="text-sm font-black text-white mb-3 uppercase tracking-wider">Accord de Consentement</h3>
                    <ul class="space-y-2.5 mb-7">
                        @foreach([
                            'Je comprends que la téléconsultation a des limites comparées à une séance en présentiel.',
                            'Je m\'engage à me trouver dans un espace calme et privé pour cette séance.',
                            "Cette séance ne sera pas enregistrée sans l'accord explicite des deux parties.",
                            "En cas de problème technique, le thérapeute me contactera via le numéro fourni dans mon profil.",
                            "En cas d'urgence médicale ou de pensées suicidaires, j'appellerai le 15 (SAMU) ou le 3114 (numéro national de prévention du suicide).",
                        ] as $item)
                        <li class="flex items-start gap-3 text-xs text-slate-300 leading-relaxed">
                            <span class="mt-0.5 flex-shrink-0 w-4 h-4 rounded-full bg-violet-700/40 border border-violet-600/60 flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>

                    <form action="{{ route('teletherapy.consent.submit', $consultation->id) }}" method="POST">
                        @csrf
                        <label class="flex items-start gap-3 bg-slate-900/50 border border-slate-700/50 rounded-xl px-4 py-3 cursor-pointer mb-5 group">
                            <input type="checkbox" name="consent" required class="mt-0.5 rounded border-slate-600 bg-slate-800 text-violet-500 focus:ring-violet-500">
                            <span class="text-xs text-slate-300 leading-relaxed">J'ai lu, compris et j'accepte les conditions de cet accord de téléconsultation. Je consens à participer à cette séance.</span>
                        </label>
                        @error('consent')<p class="text-red-400 text-xs mb-4">{{ $message }}</p>@enderror

                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-black rounded-2xl transition shadow-lg shadow-violet-900/40 flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            J'accepte — Entrer dans la salle
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @else
        {{-- ── CALL ROOM ────────────────────────────────────────────────────── --}}
        <div class="h-full w-full" x-data="callRoom()" x-init="init()" @beforeunload.window="dispose()">

        {{-- Header Overlay --}}
        <div class="session-header">
            <div class="flex items-center gap-4">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-violet-500 to-indigo-500 flex items-center justify-center">
                        <span class="text-white font-black text-xs">AF</span>
                    </div>
                    <span class="font-black text-sm hidden sm:block">IYI</span>
                </a>

                <div class="h-5 w-px bg-white/10"></div>

                {{-- Session info --}}
                <div>
                    <p class="text-white font-bold text-sm leading-tight">
                        {{ Auth::id() === $consultation->patient_id ? $consultation->therapistProfile->user->name : $consultation->patient->name }}
                    </p>
                    <p class="text-slate-400 text-[11px]">{{ ucfirst($consultation->type) }} · Réf. {{ $consultation->reference }}</p>
                </div>

                <div class="status-pill ml-2">
                    <span class="dot"></span>
                    En cours
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Timer --}}
                <div class="flex items-center gap-2 bg-black/30 border border-white/10 rounded-xl px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                    <span class="text-white font-mono text-sm font-bold" x-text="formatTime(elapsed)">00:00</span>
                </div>

                {{-- Sidebar toggle: Chat --}}
                <button @click="togglePanel('chat')"
                    class="p-2 rounded-xl transition text-sm"
                    :class="panel === 'chat' ? 'bg-violet-600 text-white' : 'bg-white/5 text-slate-400 hover:text-white hover:bg-white/10'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </button>

                @if(Auth::id() !== $consultation->patient_id)
                {{-- Sidebar toggle: Notes (therapist only) --}}
                <button @click="togglePanel('notes')"
                    class="p-2 rounded-xl transition"
                    :class="panel === 'notes' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400 hover:text-white hover:bg-white/10'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                @endif
            </div>
        </div>

        {{-- Main video container --}}
        <div id="jitsi-container"></div>

        {{-- Floating Controls Bar --}}
        <div class="controls-bar">
            {{-- Microphone --}}
            <button class="ctrl-btn" :class="micMuted ? 'muted' : 'active'" @click="toggleMic()" title="Microphone">
                <svg x-show="!micMuted" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                <svg x-show="micMuted" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
            </button>

            {{-- Camera --}}
            <button class="ctrl-btn" :class="camOff ? 'muted' : 'active'" @click="toggleCam()" title="Caméra">
                <svg x-show="!camOff" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <svg x-show="camOff" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z M3 3l18 18"/></svg>
            </button>

            {{-- Screen share --}}
            <button class="ctrl-btn active" @click="shareScreen()" title="Partage d'écran">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </button>

            <div class="h-8 w-px bg-white/10 mx-1"></div>

            {{-- End call --}}
            <a href="{{ Auth::id() === $consultation->patient_id ? route('teletherapy.feedback', $consultation->id) : route('therapist.consultations') }}"
               @click.prevent="endCall($el.href)"
               class="ctrl-btn end-call" title="Terminer la séance">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"/></svg>
            </a>
        </div>

        {{-- Sidebar panels --}}
        <div class="sidebar-panel" :class="panel ? 'open' : ''">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/06">
                <h3 class="text-white font-black text-sm" x-text="panel === 'chat' ? '💬 Chat de séance' : '📝 Notes cliniques'"></h3>
                <button @click="panel = null" class="text-slate-400 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <div class="flex-1 overflow-hidden h-full">
                <div x-show="panel === 'chat'" class="h-full">
                    @livewire('teletherapy.teletherapy-chat', ['consultation' => $consultation])
                </div>
                @if(Auth::id() !== $consultation->patient_id)
                <div x-show="panel === 'notes'" class="h-full">
                    @livewire('teletherapy.teletherapy-notes', ['consultation' => $consultation])
                </div>
                @endif
            </div>
        </div>

        {{-- Jitsi Meet API --}}
        <script src="https://meet.jit.si/external_api.js"></script>

        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('callRoom', () => ({
                panel: null,
                elapsed: 0,
                _timer: null,
                api: null,
                micMuted: false,
                camOff: false,

                init() {
                    // Prevent body scroll during the call
                    document.body.style.overflow = 'hidden';

                    // Timer
                    this._timer = setInterval(() => this.elapsed++, 1000);

                    // Jitsi setup
                    const domain = 'meet.jit.si';
                    this.api = new JitsiMeetExternalAPI(domain, {
                        roomName: 'AFIYI_Consultation_{{ $consultation->id }}_{{ $consultation->reference }}',
                        width: '100%',
                        height: '100%',
                        parentNode: document.querySelector('#jitsi-container'),
                        userInfo: {
                            displayName: '{{ Auth::user()->name }}',
                            email: '{{ Auth::user()->email }}'
                        },
                        configOverwrite: {
                            startWithAudioMuted: false,
                            startWithVideoMuted: false,
                            prejoinPageEnabled: false,
                            disableDeepLinking: true,
                            enableNoisyMicDetection: false,
                            startScreenSharing: false,
                        },
                        interfaceConfigOverwrite: {
                            TOOLBAR_BUTTONS: [],          // We use our own controls
                            SHOW_JITSI_WATERMARK: false,
                            SHOW_BRAND_WATERMARK: false,
                            SHOW_POWERED_BY: false,
                            DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
                            HIDE_INVITE_MORE_HEADER: true,
                        }
                    });

                    // Sync state with Jitsi events
                    this.api.on('audioMuteStatusChanged', ({ muted }) => { this.micMuted = muted; });
                    this.api.on('videoMuteStatusChanged', ({ muted }) => { this.camOff = muted; });
                },

                toggleMic() {
                    if (this.api) this.api.executeCommand('toggleAudio');
                },
                toggleCam() {
                    if (this.api) this.api.executeCommand('toggleVideo');
                },
                shareScreen() {
                    if (this.api) this.api.executeCommand('toggleShareScreen');
                },

                togglePanel(p) {
                    this.panel = this.panel === p ? null : p;
                },

                endCall(href) {
                    if (confirm('Êtes-vous sûr de vouloir terminer la séance ?')) {
                        this.dispose();
                        window.location.href = href;
                    }
                },

                dispose() {
                    if (this._timer) clearInterval(this._timer);
                    if (this.api) this.api.dispose();
                },

                formatTime(s) {
                    const m = Math.floor(s / 60).toString().padStart(2, '0');
                    const sec = (s % 60).toString().padStart(2, '0');
                    return `${m}:${sec}`;
                }
            }));
        });
        </script>
        </div>
    @endif
</body>
</html>
