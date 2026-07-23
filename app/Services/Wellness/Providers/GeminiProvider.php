<?php

namespace App\Services\Wellness\Providers;

use App\Services\Wellness\Contracts\LlmProviderInterface;

class GeminiProvider implements LlmProviderInterface
{
    public function generateResponse(string $prompt, array $options = []): string
    {
        // Simple fallback rules to act like an empathetic wellness coach
        $promptLower = strtolower($prompt);

        if (str_contains($promptLower, 'stress') || str_contains($promptLower, 'anxi')) {
            return "Je comprends tout à fait que cette situation génère du stress ou de l'anxiété en vous. Sachez que ces ressentis sont légitimes. Prenez une inspiration lente par le nez, retenez-la un instant, puis expirez doucement. Pour vous détendre, je vous conseille d'essayer un exercice de respiration guidée ou d'allumer une bougie apaisante.";
        }

        if (str_contains($promptLower, 'sommeil') || str_contains($promptLower, 'dormir')) {
            return "Les difficultés de sommeil sont souvent liées à une surcharge mentale. Pour préparer votre nuit, essayez d'instaurer une routine sans écran 30 minutes avant le coucher. Vous pouvez également allumer une bougie parfumée à la lavande ou utiliser des huiles essentielles calmantes.";
        }

        if (str_contains($promptLower, 'seul') || str_contains($promptLower, 'isolement')) {
            return "Il est tout à fait naturel de ressentir de la solitude parfois. Notre module Destiny Connection est conçu pour vous mettre en relation avec une autre personne disponible pour échanger en toute bienveillance et anonymat. N'hésitez pas à lancer un match !";
        }

        return "Je suis là pour vous écouter et vous accompagner dans votre parcours de bien-être. Dites-m'en plus sur ce que vous ressentez, ou faites-moi savoir si vous souhaitez essayer une activité relaxante.";
    }
}
