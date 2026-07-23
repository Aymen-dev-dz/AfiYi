<div {{ $attributes->merge(['class' => 'flex items-center gap-3 group cursor-pointer']) }}>
    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500/10 via-purple-500/10 to-teal-500/10 p-2 backdrop-blur-xl border border-indigo-500/20 shadow-lg shadow-indigo-500/10 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <defs>
                <linearGradient id="afiyi-mark-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#4F46E5" />
                    <stop offset="35%" stop-color="#7C3AED" />
                    <stop offset="70%" stop-color="#06B6D4" />
                    <stop offset="100%" stop-color="#10B981" />
                </linearGradient>
            </defs>
            <path d="M50 76 C35 64 22 50 22 36 C22 26 29 20 38 20 C44 20 48 23 50 27 C52 23 56 20 62 20 C71 20 78 26 78 36 C78 50 65 64 50 76 Z" 
                  stroke="url(#afiyi-mark-grad)" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <circle cx="38" cy="34" r="4.5" fill="#4F46E5" />
            <circle cx="62" cy="34" r="4.5" fill="#06B6D4" />
            <circle cx="50" cy="56" r="5" fill="#10B981" />
            <path d="M38 34 Q50 45 62 34 Q50 65 38 34 Z" fill="url(#afiyi-mark-grad)" opacity="0.35"/>
        </svg>
    </div>
    <div class="flex flex-col">
        <span class="text-2xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-teal-400 bg-clip-text text-transparent tracking-tighter leading-none">AFIYI</span>
        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">AI Mental Wellness</span>
    </div>
</div>
