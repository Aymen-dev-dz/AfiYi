<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AF IYI') }} — {{ __('landing.hero.badge') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        
        /* Glassmorphism */
        .glassmorphism {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .dark .glassmorphism {
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Gradient Orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            z-index: 0;
            pointer-events: none;
            animation: float-orb 15s ease-in-out infinite alternate;
        }
        @keyframes float-orb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -50px) scale(1.1); }
            100% { transform: translate(-20px, 20px) scale(0.9); }
        }

        /* Animations */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 100ms; }
        .reveal-delay-2 { transition-delay: 200ms; }
        .reveal-delay-3 { transition-delay: 300ms; }

        .floating { animation: float 6s ease-in-out infinite; }
        .floating-delay { animation: float 6s ease-in-out 3s infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen relative overflow-x-hidden selection:bg-indigo-500 selection:text-white transition-colors duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    
    <!-- Background Orbs -->
    <div class="glow-orb w-[500px] h-[500px] bg-emerald-500/20 top-[-100px] left-[-100px]"></div>
    <div class="glow-orb w-[600px] h-[600px] bg-teal-500/20 bottom-[20%] right-[-100px]" style="animation-delay: -5s;"></div>
    <div class="glow-orb w-[400px] h-[400px] bg-indigo-500/15 top-[40%] left-[10%]" style="animation-delay: -10s;"></div>

    <!-- Navigation -->
    <div class="fixed top-4 left-0 right-0 z-50 flex justify-center px-4 sm:px-6 pointer-events-none">
        <nav :class="{'bg-white/60 dark:bg-gray-900/40 backdrop-blur-2xl border-white/50 dark:border-white/10 shadow-xl shadow-indigo-900/10': scrolled, 'bg-transparent border-transparent': !scrolled}" class="pointer-events-auto w-full max-w-7xl rounded-3xl border transition-all duration-300" x-data="{ mobileMenuOpen: false }">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <div class="flex items-center space-x-3">
                        <x-application-mark class="h-8 lg:h-10" />
                    </div>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                        <a href="#features" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Fonctionnalités</a>
                        <a href="#how-it-works" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Comment ça marche</a>
                        <a href="#pricing" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tarifs</a>
                        
                        <!-- Language Switcher -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-1 text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition">
                                <span>{{ strtoupper(app()->getLocale()) }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-24 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-white/50 dark:border-slate-700 rounded-xl shadow-lg py-2">
                                <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-slate-700/50 text-center font-bold {{ app()->getLocale() == 'fr' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-700 dark:text-slate-300' }}">FR</a>
                                <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-slate-700/50 text-center font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-700 dark:text-slate-300' }}">EN</a>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold shadow-[0_2px_10px_rgba(0,0,0,0.1)] hover:scale-105 transition transform">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition">Connexion</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">Démarrer</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Nav -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-200/50 dark:border-slate-800/50 p-4 space-y-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-b-3xl">
                <div class="flex justify-center space-x-4 pb-4 border-b border-slate-200/50 dark:border-slate-800/50">
                    <a href="{{ route('lang.switch', 'fr') }}" class="text-sm font-bold {{ app()->getLocale() == 'fr' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400' }}">FR</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400' }}">EN</a>
                </div>
                <a href="#features" class="block px-3 py-2 text-center font-bold text-slate-700 dark:text-slate-300">Fonctionnalités</a>
                <a href="#pricing" class="block px-3 py-2 text-center font-bold text-slate-700 dark:text-slate-300">Tarifs</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full py-3 text-center rounded-xl bg-indigo-600 text-white font-bold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-center font-bold text-slate-700 dark:text-slate-300">Connexion</a>
                    <a href="{{ route('register') }}" class="block w-full py-3 text-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30">Démarrer</a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- 1. HERO SECTION (WORLD-CLASS SAAS DESIGN) -->
    <section class="relative min-h-screen flex flex-col justify-between pt-28 pb-12 lg:pt-36 lg:pb-16 overflow-hidden z-10" x-data="{ 
        shown: false, 
        activePreview: 'ai',
        aiQuery: 'Comment apaiser mon anxiété avant de dormir ?',
        aiAnswer: 'Prenez 3 respirations profondes 4-7-8. Notre tisane Sérénité et une courte méditation guidée peuvent apaiser votre esprit en 5 minutes.',
        isTyping: false,
        mouseX: 0,
        mouseY: 0
    }" x-init="shown = true; mouseX = window.innerWidth / 2; mouseY = window.innerHeight / 2" @mousemove.document="mouseX = $event.clientX; mouseY = $event.clientY">

        <!-- Floating Emotion Particles -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute top-[15%] left-[8%] px-4 py-2 rounded-2xl glassmorphism text-xs font-bold text-indigo-600 dark:text-indigo-300 shadow-xl floating flex items-center gap-2 border border-indigo-200/50 dark:border-indigo-800/40">
                <span class="text-base">🌸</span> Sérénité
            </div>
            <div class="absolute top-[25%] right-[10%] px-4 py-2 rounded-2xl glassmorphism text-xs font-bold text-emerald-600 dark:text-emerald-300 shadow-xl floating-delay flex items-center gap-2 border border-emerald-200/50 dark:border-emerald-800/40">
                <span class="text-base">🌿</span> Équilibre & Croissance
            </div>
            <div class="absolute bottom-[28%] left-[12%] px-4 py-2 rounded-2xl glassmorphism text-xs font-bold text-amber-600 dark:text-amber-300 shadow-xl floating flex items-center gap-2 border border-amber-200/50 dark:border-amber-800/40">
                <span class="text-base">🌟</span> Espoir & Confiance
            </div>
            <div class="absolute bottom-[20%] right-[14%] px-4 py-2 rounded-2xl glassmorphism text-xs font-bold text-cyan-600 dark:text-cyan-300 shadow-xl floating-delay flex items-center gap-2 border border-cyan-200/50 dark:border-cyan-800/40">
                <span class="text-base">💡</span> clarté Mentale
            </div>
            <div class="absolute top-[45%] left-[4%] px-3 py-1.5 rounded-full glassmorphism text-xs font-bold text-purple-600 dark:text-purple-300 shadow-lg floating hidden md:flex items-center gap-1.5">
                <span class="text-sm">💜</span> Empathie
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 flex flex-col lg:flex-row items-center gap-12 lg:gap-16 z-10 my-auto">
            
            <!-- Left Hero Text & Headline -->
            <div class="flex-1 text-center lg:text-left reveal" :class="shown ? 'active' : ''">
                <!-- Platform Badge -->
                <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-teal-500/10 backdrop-blur-xl px-4 py-2 rounded-full border border-indigo-500/20 shadow-md mb-6 hover:scale-105 transition-transform cursor-pointer">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-teal-500 bg-clip-text text-transparent uppercase tracking-widest">
                        Plateforme IA & Santé Mentale
                    </span>
                </div>

                <!-- World-Class Headline -->
                <h1 class="text-5xl sm:text-6xl lg:text-[5rem] font-black tracking-tighter leading-[1.05] text-slate-900 dark:text-white mb-6 drop-shadow-sm">
                    Helping Minds.<br>
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-cyan-500 bg-clip-text text-transparent drop-shadow-sm">Connecting Hearts.</span><br>
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-500 to-indigo-500 bg-clip-text text-transparent drop-shadow-sm">Changing Lives.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg lg:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto lg:mx-0 mb-8 leading-relaxed font-medium">
                    L'écosystème de bien-être mental augmenté par l'IA. Téléconsultations personnalisées, rencontres anonymes Destiny et soins holistiques pour restaurer votre équilibre.
                </p>

                <!-- Interactive Call To Actions -->
                <div class="flex flex-col sm:flex-row flex-wrap gap-4 justify-center lg:justify-start mb-10">
                    <a href="{{ route('teletherapy.directory') }}" class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 text-white rounded-2xl text-sm font-black shadow-xl shadow-indigo-500/30 hover:scale-105 hover:shadow-indigo-500/50 transition-all overflow-hidden flex items-center justify-center gap-3">
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="text-lg">🩺</span> 
                        <span class="relative z-10">Consulter un Psychologue</span>
                    </a>
                    
                    <a href="{{ route('destiny.lobby') }}" class="px-7 py-4 glassmorphism border border-purple-500/30 hover:border-purple-500/60 hover:bg-purple-50/40 dark:hover:bg-purple-900/30 text-slate-800 dark:text-white rounded-2xl text-sm font-bold shadow-md hover:scale-105 transition-all text-center flex items-center justify-center gap-2">
                        <span>🌌</span> Salon Destiny
                    </a>

                    <a href="{{ route('marketplace.index') }}" class="px-7 py-4 glassmorphism border border-teal-500/30 hover:border-teal-500/60 hover:bg-teal-50/40 dark:hover:bg-teal-900/30 text-slate-800 dark:text-white rounded-2xl text-sm font-bold shadow-md hover:scale-105 transition-all text-center flex items-center justify-center gap-2">
                        <span>🛍️</span> Boutique Wellness
                    </a>
                </div>

                <!-- Animated Live Statistics Counter -->
                <div class="grid grid-cols-3 gap-6 pt-6 border-t border-slate-200/60 dark:border-slate-800/60 max-w-lg mx-auto lg:mx-0">
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">500+</div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5">Psychologues</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">10k+</div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5">Membres Actifs</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">98%</div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5">Satisfaction</div>
                    </div>
                </div>
            </div>

            <!-- Right Interactive Previews Window (SaaS Card Suite) -->
            <div class="flex-1 w-full max-w-xl relative z-10 reveal reveal-delay-2" :class="shown ? 'active' : ''">
                
                <!-- Card Container with Tilt & 3D Effect -->
                <div class="relative rounded-3xl glassmorphism p-6 sm:p-8 shadow-2xl border border-white/60 dark:border-white/10 transition-transform duration-300 ease-out"
                     :style="`transform: perspective(1000px) rotateX(${(mouseY - window.innerHeight/2) * -0.008}deg) rotateY(${(mouseX - window.innerWidth/2) * 0.008}deg)`">
                    
                    <!-- Decorative Radial Glow -->
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-gradient-to-tr from-indigo-500/30 to-purple-500/30 blur-3xl rounded-full pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-gradient-to-tr from-teal-500/30 to-emerald-500/30 blur-3xl rounded-full pointer-events-none"></div>

                    <!-- Interactive Switcher Header -->
                    <div class="flex items-center justify-between gap-2 p-1.5 bg-slate-200/50 dark:bg-slate-800/60 rounded-2xl mb-6 backdrop-blur-md border border-slate-300/30 dark:border-slate-700/30">
                        <button @click="activePreview = 'ai'" :class="activePreview === 'ai' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5">
                            <span>🤖</span> Assistant IA
                        </button>
                        <button @click="activePreview = 'therapist'" :class="activePreview === 'therapist' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5">
                            <span>🩺</span> Thérapeute
                        </button>
                        <button @click="activePreview = 'destiny'" :class="activePreview === 'destiny' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5">
                            <span>🌌</span> Destiny
                        </button>
                        <button @click="activePreview = 'market'" :class="activePreview === 'market' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5">
                            <span>🛍️</span> Boutique
                        </button>
                    </div>

                    <!-- TAB 1: AI Assistant Live Preview -->
                    <div x-show="activePreview === 'ai'" x-transition class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200/50 dark:border-slate-700/50 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-black text-sm shadow-md">
                                    ✨
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900 dark:text-white">AFIYI AI Companion</h4>
                                    <span class="text-[10px] text-emerald-500 font-bold flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> En ligne 24/7
                                    </span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase">
                                IA Empathetic
                            </span>
                        </div>

                        <!-- User Prompt Bubble -->
                        <div class="p-3.5 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl text-xs text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                            <p class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Vous :</p>
                            <p x-text="aiQuery"></p>
                        </div>

                        <!-- AI Response Bubble -->
                        <div class="p-4 bg-white/80 dark:bg-slate-800/80 rounded-2xl border border-white/60 dark:border-slate-700/60 shadow-sm text-xs leading-relaxed space-y-2">
                            <p class="font-bold text-purple-600 dark:text-purple-400 flex items-center gap-1">
                                <span>🤖</span> Réponse AFIYI AI :
                            </p>
                            <p class="text-slate-700 dark:text-slate-300 font-medium" x-text="aiAnswer"></p>
                            <div class="pt-2 flex flex-wrap gap-2">
                                <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-lg text-[10px] font-bold">
                                    🌿 Respiration 4-7-8
                                </span>
                                <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 rounded-lg text-[10px] font-bold">
                                    ☕ Thé Sérénité
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('wellness.space') }}" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs rounded-xl shadow-md hover:scale-[1.02] transition transform flex items-center justify-center gap-2">
                            Lancer une séance guidée par l'IA ➔
                        </a>
                    </div>

                    <!-- TAB 2: Therapist Live Preview Card -->
                    <div x-show="activePreview === 'therapist'" x-transition class="space-y-4">
                        <div class="p-4 bg-white/80 dark:bg-slate-800/80 rounded-2xl border border-white/60 dark:border-slate-700/60 shadow-sm space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center text-2xl border border-indigo-200 dark:border-indigo-800">
                                    👩‍⚕️
                                </div>
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-black text-slate-900 dark:text-white text-base">Dr. Emma Watson</h4>
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 text-[10px] font-extrabold rounded-md">★ 5.0</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Psychologue Clinicienne & Spécialiste Anxiété</p>
                                    <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">450+ Consultations Réalisées</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-200/50 dark:border-slate-700/50 text-xs">
                                <div>
                                    <span class="text-slate-400 text-[10px]">Tarif séance :</span>
                                    <p class="font-black text-slate-900 dark:text-white">3 000 DZD <span class="text-[10px] font-normal text-slate-400">/ 45 min</span></p>
                                </div>
                                <a href="{{ route('teletherapy.directory') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm transition">
                                    Réserver RDV
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Destiny Connection Preview -->
                    <div x-show="activePreview === 'destiny'" x-transition class="space-y-4">
                        <div class="p-4 bg-gradient-to-br from-purple-900/20 via-indigo-900/20 to-slate-900/20 rounded-2xl border border-purple-500/30 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">🌌</span>
                                    <h4 class="font-black text-slate-900 dark:text-white text-sm">Destiny Connection</h4>
                                </div>
                                <span class="px-2.5 py-1 bg-green-500/20 text-green-600 dark:text-green-300 border border-green-500/30 rounded-full text-[10px] font-black uppercase">
                                    En Ligne
                                </span>
                            </div>
                            <div class="p-3 bg-white/40 dark:bg-slate-800/40 rounded-xl text-xs space-y-1">
                                <p class="font-bold text-purple-600 dark:text-purple-400">Match Anonyme instantané :</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">Vous êtes mis en relation avec une personne bienveillante disposée à échanger. Cryptage AES-256 garanti.</p>
                            </div>
                            <a href="{{ route('destiny.lobby') }}" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition text-center block">
                                Entrer dans le Salon Anonyme ➔
                            </a>
                        </div>
                    </div>

                    <!-- TAB 4: Marketplace Preview -->
                    <div x-show="activePreview === 'market'" x-transition class="space-y-4">
                        <div class="p-4 bg-white/80 dark:bg-slate-800/80 rounded-2xl border border-white/60 dark:border-slate-700/60 shadow-sm flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950 flex items-center justify-center text-2xl">
                                    🍵
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900 dark:text-white text-xs">Tisane Infusion Sérénité Bio</h4>
                                    <p class="text-[10px] text-slate-400 font-bold">1 800 DZD</p>
                                </div>
                            </div>
                            <a href="{{ route('marketplace.index') }}" class="px-3 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-sm transition">
                                Voir Produit
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Smooth Scroll Indicator at Bottom of Hero -->
        <div class="relative z-20 flex flex-col items-center justify-center pt-8 cursor-pointer group" @click="document.getElementById('features').scrollIntoView({ behavior: 'smooth' })">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 group-hover:text-indigo-500 transition-colors mb-2">
                Explorer l'Écosystème
            </span>
            <div class="w-6 h-10 rounded-full border-2 border-slate-300 dark:border-slate-700 flex justify-center p-1 group-hover:border-indigo-500 transition-colors">
                <div class="w-1.5 h-3 bg-indigo-500 rounded-full animate-bounce"></div>
            </div>
        </div>

    </section>

    <!-- 2. FEATURES BENTO GRID -->
    <section id="features" class="py-24 relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal" :class="shown ? 'active' : ''">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">{{ __('landing.bento.title') }}</h2>
                <p class="text-xl text-slate-500 dark:text-slate-400">{{ __('landing.bento.subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px]">
                
                <!-- Marketplace -->
                <div class="md:col-span-2 bg-gradient-to-br from-orange-50 to-rose-50 dark:from-slate-900 dark:to-slate-900 rounded-[2rem] p-8 border border-orange-100 dark:border-slate-800 relative overflow-hidden group reveal reveal-delay-1" :class="shown ? 'active' : ''">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-400/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 transition-transform group-hover:scale-150"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm mb-6 text-orange-500">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('landing.bento.marketplace_title') }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 max-w-sm">{{ __('landing.bento.marketplace_desc') }}</p>
                        </div>
                        <a href="{{ route('marketplace.index') }}" class="inline-flex font-bold text-orange-600 dark:text-orange-400 hover:text-orange-700 transition">{!! __('landing.bento.marketplace_link') !!}</a>
                    </div>
                </div>

                <!-- AI Wellness -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-slate-900 dark:to-slate-900 rounded-[2rem] p-8 border border-emerald-100 dark:border-slate-800 relative overflow-hidden group reveal reveal-delay-2" :class="shown ? 'active' : ''">
                    <div class="absolute bottom-0 right-0 w-48 h-48 bg-emerald-400/10 rounded-full blur-2xl transform translate-x-1/2 translate-y-1/2 transition-transform group-hover:scale-150"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm mb-6 text-emerald-500">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('landing.bento.ai_title') }}</h3>
                            <p class="text-slate-600 dark:text-slate-400">{{ __('landing.bento.ai_desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Teletherapy -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-900 dark:to-slate-900 rounded-[2rem] p-8 border border-blue-100 dark:border-slate-800 relative overflow-hidden group reveal reveal-delay-1" :class="shown ? 'active' : ''">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2 transition-transform group-hover:scale-150"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm mb-6 text-blue-500">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('landing.bento.teletherapy_title') }}</h3>
                            <p class="text-slate-600 dark:text-slate-400">{{ __('landing.bento.teletherapy_desc') }}</p>
                        </div>
                        <a href="{{ route('teletherapy.directory') }}" class="inline-flex font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition">{!! __('landing.bento.teletherapy_link') !!}</a>
                    </div>
                </div>

                <!-- Destiny Connection -->
                <div class="md:col-span-2 bg-slate-900 dark:bg-slate-950 rounded-[2rem] p-8 border border-slate-800 relative overflow-hidden group reveal reveal-delay-2" :class="shown ? 'active' : ''">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNSkiLz48L3N2Zz4=')] opacity-30"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm mb-6 text-purple-400 border border-slate-700">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2">{{ __('landing.bento.destiny_title') }}</h3>
                            <p class="text-slate-400 max-w-sm">{{ __('landing.bento.destiny_desc') }}</p>
                        </div>
                        <a href="{{ route('destiny.lobby') }}" class="inline-flex font-bold text-purple-400 hover:text-purple-300 transition">{!! __('landing.bento.destiny_link') !!}</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CONCEPT PRESENTATION -->
    <section class="py-24 bg-white dark:bg-slate-900 border-t border-slate-200/50 dark:border-slate-800/50" x-data="{ shown: false }" x-intersect.once="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal" :class="shown ? 'active' : ''">
                <span class="text-indigo-600 dark:text-indigo-400 font-bold tracking-wider uppercase text-sm mb-2 block">NOTRE CONCEPT & MISSION</span>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">Une approche globale de la santé mentale</h2>
                <p class="text-xl text-slate-500 dark:text-slate-400">
                    Nous combinons les bienfaits du quotidien, l'entraide communautaire et la thérapie professionnelle pour vous offrir le meilleur accompagnement possible.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Concept 1 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-4xl mb-6">🛍️</div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-3">La Boutique Bien-être</h3>
                        <p class="text-sm text-slate-550 dark:text-slate-400 leading-relaxed mb-6">
                            Découvrez une sélection de produits de haute qualité : bougies aromatiques, thés et infusions apaisantes, huiles essentielles et journaux de réflexion pour vos rituels quotidiens de relaxation.
                        </p>
                    </div>
                    <a href="{{ route('marketplace.index') }}" class="text-xs font-black text-orange-600 dark:text-orange-400 hover:underline">Accéder au Marketplace &rarr;</a>
                </div>

                <!-- Concept 2 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-4xl mb-6">💬</div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-3">La Communauté Destiny</h3>
                        <p class="text-sm text-slate-550 dark:text-slate-400 leading-relaxed mb-6">
                            Parlez de manière totalement anonyme avec d'autres patients de la plateforme via notre salon Destiny. Échangez sur vos vécus, partagez vos conseils et brisez l'isolement en toute bienveillance.
                        </p>
                    </div>
                    <a href="{{ route('destiny.lobby') }}" class="text-xs font-black text-purple-600 dark:text-purple-400 hover:underline">Rejoindre le salon Destiny &rarr;</a>
                </div>

                <!-- Concept 3 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-4xl mb-6">🩺</div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-3">Thérapie Professionnelle</h3>
                        <p class="text-sm text-slate-550 dark:text-slate-400 leading-relaxed mb-6">
                            Prenez rendez-vous pour des téléconsultations privées par vidéo avec des psychologues et psychothérapeutes certifiés et rigoureusement sélectionnés. Bénéficiez d'un suivi sur-mesure.
                        </p>
                    </div>
                    <a href="{{ route('teletherapy.directory') }}" class="text-xs font-black text-blue-600 dark:text-blue-400 hover:underline">Consulter le répertoire &rarr;</a>
                </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="py-24 bg-white dark:bg-slate-900 border-t border-slate-200/50 dark:border-slate-800/50" x-data="{ shown: false }" x-intersect.once="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal" :class="shown ? 'active' : ''">
                <span class="text-indigo-650 dark:text-indigo-400 font-bold tracking-wider uppercase text-sm mb-2 block">TÉMOIGNAGES CLIENTS</span>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">Ils ont trouvé leur équilibre avec AF IYI</h2>
                <p class="text-xl text-slate-500 dark:text-slate-400">
                    Découvrez les retours d'expérience et témoignages inspirants de notre communauté de bien-être.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-3xl text-indigo-500 mb-4">“</div>
                        <p class="text-xs text-slate-650 dark:text-slate-300 leading-relaxed italic mb-6">
                            La téléconsultation sur AF IYI a complètement changé mon regard sur la thérapie. J'ai pu trouver un psychologue compétent en 5 minutes et planifier mes séances en visio à mon rythme. Le suivi est simple, discret et extrêmement bienveillant.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-lg font-bold">👩‍💼</div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 dark:text-white">Sarah M.</h4>
                            <p class="text-[9px] text-slate-450 uppercase font-black tracking-wider">Patiente en téléthérapie</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-3xl text-indigo-500 mb-4">“</div>
                        <p class="text-xs text-slate-650 dark:text-slate-300 leading-relaxed italic mb-6">
                            Après mon déménagement, je me sentais très seul et stressé. Le salon d'échange anonyme Destiny m'a permis de me connecter à des personnes qui traversaient exactement les mêmes difficultés scolaires et sociales. Briser la solitude m'a fait énormément de bien.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-lg font-bold">🧑‍💻</div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 dark:text-white">Lucas D.</h4>
                            <p class="text-[9px] text-slate-450 uppercase font-black tracking-wider">Membre Communauté Destiny</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-slate-50 dark:bg-slate-800/30 p-8 rounded-[2rem] border border-slate-150 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="text-3xl text-indigo-500 mb-4">“</div>
                        <p class="text-xs text-slate-650 dark:text-slate-300 leading-relaxed italic mb-6">
                            Les bougies artisanales et l'infusion Sommeil Profond achetées sur le Marketplace d'AF IYI font maintenant partie intégrante de ma routine du soir. La qualité des produits est exceptionnelle et cela m'aide à déconnecter complètement après des journées chargées.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center text-lg font-bold">👩‍🎨</div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 dark:text-white">Amélie R.</h4>
                            <p class="text-[9px] text-slate-450 uppercase font-black tracking-wider">Patiente & Client de la boutique</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. HOW IT WORKS -->
    <section id="how-it-works" class="py-24 bg-white dark:bg-slate-900 border-y border-slate-200/50 dark:border-slate-800/50" x-data="{ shown: false }" x-intersect.once="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 reveal" :class="shown ? 'active' : ''">
                <span class="text-indigo-600 dark:text-indigo-400 font-bold tracking-wider uppercase text-sm mb-2 block">{{ __('landing.how_it_works.badge') }}</span>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">{{ __('landing.how_it_works.title') }}</h2>
                <p class="text-xl text-slate-500 dark:text-slate-400">{{ __('landing.how_it_works.subtitle') }}</p>
            </div>

            <div class="relative">
                <!-- Connecting line -->
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-100 via-purple-200 to-pink-100 dark:from-slate-800 dark:via-indigo-900 dark:to-slate-800"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center text-center reveal reveal-delay-1" :class="shown ? 'active' : ''">
                        <div class="w-24 h-24 rounded-3xl bg-white dark:bg-slate-800 border-4 border-indigo-50 dark:border-slate-700 shadow-xl flex items-center justify-center mb-6 transform hover:rotate-3 transition duration-300">
                            <span class="text-3xl font-black bg-gradient-to-br from-indigo-500 to-purple-500 bg-clip-text text-transparent">1</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ __('landing.how_it_works.step_1_title') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400">{{ __('landing.how_it_works.step_1_desc') }}</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center text-center reveal reveal-delay-2" :class="shown ? 'active' : ''">
                        <div class="w-24 h-24 rounded-3xl bg-white dark:bg-slate-800 border-4 border-purple-50 dark:border-slate-700 shadow-xl flex items-center justify-center mb-6 transform hover:-rotate-3 transition duration-300">
                            <span class="text-3xl font-black bg-gradient-to-br from-purple-500 to-pink-500 bg-clip-text text-transparent">2</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ __('landing.how_it_works.step_2_title') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400">{{ __('landing.how_it_works.step_2_desc') }}</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center text-center reveal reveal-delay-3" :class="shown ? 'active' : ''">
                        <div class="w-24 h-24 rounded-3xl bg-white dark:bg-slate-800 border-4 border-pink-50 dark:border-slate-700 shadow-xl flex items-center justify-center mb-6 transform hover:rotate-3 transition duration-300">
                            <span class="text-3xl font-black bg-gradient-to-br from-pink-500 to-rose-500 bg-clip-text text-transparent">3</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ __('landing.how_it_works.step_3_title') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400">{{ __('landing.how_it_works.step_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. PRICING -->
    <section id="pricing" class="py-24 relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal" :class="shown ? 'active' : ''">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">{{ __('landing.pricing.title') }}</h2>
                <p class="text-xl text-slate-500 dark:text-slate-400">{{ __('landing.pricing.subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- User Pricing -->
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-xl hover:shadow-2xl transition duration-300 flex flex-col justify-between reveal reveal-delay-1" :class="shown ? 'active' : ''">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ __('landing.pricing.user_title') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2">{{ __('landing.pricing.user_subtitle') }}</p>
                        <div class="mt-8 flex items-end">
                            <span class="text-6xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('landing.pricing.user_price') }}</span>
                            <span class="text-xl font-bold text-slate-500 mb-2 ml-1">{{ __('landing.pricing.user_period') }}</span>
                        </div>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-slate-700 dark:text-slate-300 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.user_feat_1') }}
                            </li>
                            <li class="flex items-center text-slate-700 dark:text-slate-300 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.user_feat_2') }}
                            </li>
                            <li class="flex items-center text-slate-700 dark:text-slate-300 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.user_feat_3') }}
                            </li>
                        </ul>
                    </div>
                    <div class="mt-10">
                        <a href="{{ route('premium.index') }}" class="block w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black rounded-2xl text-center hover:scale-[1.02] transition transform shadow-lg">{{ __('landing.pricing.user_cta') }}</a>
                    </div>
                </div>

                <!-- Therapist Pricing -->
                <div class="bg-gradient-to-b from-indigo-900 to-slate-900 dark:from-indigo-950 dark:to-slate-950 rounded-[2.5rem] p-10 border border-indigo-800 shadow-2xl relative overflow-hidden flex flex-col justify-between reveal reveal-delay-2" :class="shown ? 'active' : ''">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute top-6 right-6 bg-gradient-to-r from-pink-500 to-purple-500 text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                        {{ __('landing.pricing.therapist_badge') }}
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-3xl font-black text-white">{{ __('landing.pricing.therapist_title') }}</h3>
                        <p class="text-indigo-200 mt-2">{{ __('landing.pricing.therapist_subtitle') }}</p>
                        <div class="mt-8 flex items-end">
                            <span class="text-6xl font-black tracking-tight text-white">{{ __('landing.pricing.therapist_price') }}</span>
                            <span class="text-xl font-bold text-indigo-300 mb-2 ml-1">{{ __('landing.pricing.therapist_period') }}</span>
                        </div>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-indigo-100 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.therapist_feat_1') }}
                            </li>
                            <li class="flex items-center text-indigo-100 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.therapist_feat_2') }}
                            </li>
                            <li class="flex items-center text-indigo-100 font-medium">
                                <span class="w-6 h-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center mr-3"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                                {{ __('landing.pricing.therapist_feat_3') }}
                            </li>
                        </ul>
                    </div>
                    <div class="mt-10 relative z-10">
                        <a href="{{ route('premium.index') }}" class="block w-full py-4 bg-white text-indigo-900 font-black rounded-2xl text-center hover:scale-[1.02] transition transform shadow-xl shadow-white/10">{{ __('landing.pricing.therapist_cta') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-200/50 dark:border-slate-800/50 py-16 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Branding column -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <span class="text-white font-extrabold text-lg tracking-wider">AF</span>
                        </div>
                        <span class="text-2xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent tracking-tight">IYI</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        AF IYI est une plateforme globale dédiée à l'accompagnement du bien-être mental, associant boutique de santé, espace communautaire et téléconsultation psychologique.
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-200/50 dark:border-slate-800/50 pt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} AF IYI. Tous droits réservés.
            </div>
        </div>
    </footer>
    <x-quick-nav />
</body>
</html>
