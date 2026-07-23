<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Donnez votre avis sur la consultation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden p-8 border border-slate-100 dark:border-gray-700">
                <div class="text-center space-y-4 mb-6">
                    <span class="text-4xl">🙏</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">Merci pour votre confiance</h3>
                    <p class="text-xs text-slate-400">Votre évaluation est précieuse pour nous aider à garantir des consultations de haute qualité.</p>
                </div>

                <form action="{{ route('teletherapy.feedback.submit', $consultation->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Rating -->
                    <div class="space-y-2 text-center" x-data="{ rating: 5 }">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Note globale</label>
                        <input type="hidden" name="rating" :value="rating">
                        <div class="flex justify-center gap-2">
                            <template x-for="i in 5">
                                <button type="button" @click="rating = i" class="text-3xl focus:outline-none transition transform hover:scale-110"
                                        :class="i <= rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'">
                                    ★
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Votre témoignage (Facultatif)</label>
                        <textarea name="comment" rows="4" placeholder="Que retenez-vous de cet échange ? Votre retour peut aider d'autres personnes..."
                                  class="w-full text-xs p-3 border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                    </div>

                    <!-- Anonymous toggle -->
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Publier de manière anonyme</span>
                            <span class="text-[10px] text-slate-400">Votre prénom ne sera pas affiché sur le profil public du psychologue.</span>
                        </div>
                        <input type="checkbox" name="is_anonymous" value="1" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 bg-slate-100 dark:bg-slate-800 border-gray-300 dark:border-gray-700">
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-xs font-black shadow-lg transition">
                        Soumettre mon avis
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
