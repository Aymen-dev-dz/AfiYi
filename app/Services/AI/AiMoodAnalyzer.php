<?php

namespace App\Services\AI;

use OpenAI\Client;
use Illuminate\Support\Facades\Log;

class AiMoodAnalyzer
{
    private ?Client $client;

    public function __construct()
    {
        try {
            $apiKey = config('services.openai.api_key');
            if ($apiKey) {
                $this->client = \OpenAI::client($apiKey);
            } else {
                $this->client = null;
            }
        } catch (\Exception $e) {
            $this->client = null;
        }
    }

    /**
     * Analyzes an array of mood entries and returns insights.
     */
    public function analyzeTrends(array $moodEntries): string
    {
        if (empty($moodEntries)) {
            return "Pas assez de données pour analyser vos tendances. Continuez à enregistrer vos humeurs quotidiennes !";
        }

        // Mock response if OpenAI is not configured
        if (!$this->client) {
            return $this->getMockAnalysis($moodEntries);
        }

        try {
            $prompt = $this->buildPrompt($moodEntries);
            
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un psychologue bienveillant. Analyse les données d\'humeur de l\'utilisateur et fournis des insights et des recommandations douces en français.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 300,
            ]);

            return $response->choices[0]->message->content ?? $this->getMockAnalysis($moodEntries);
        } catch (\Exception $e) {
            Log::error('AI Mood Analysis failed: ' . $e->getMessage());
            return $this->getMockAnalysis($moodEntries);
        }
    }

    private function buildPrompt(array $moodEntries): string
    {
        $prompt = "Voici mes dernières entrées d'humeur :\n";
        foreach ($moodEntries as $entry) {
            $date = $entry['created_at'] ?? 'Date inconnue';
            $score = $entry['wellness_score'] ?? 50;
            $notes = $entry['notes'] ?? 'Aucune note';
            $prompt .= "- Date: {$date}, Score de bien-être: {$score}/100, Notes: {$notes}\n";
        }
        $prompt .= "\nQuelles tendances observes-tu ? Que me conseilles-tu pour améliorer mon bien-être ?";
        return $prompt;
    }

    private function getMockAnalysis(array $moodEntries): string
    {
        $avgScore = collect($moodEntries)->avg('wellness_score') ?? 50;
        
        if ($avgScore > 75) {
            return "Vos tendances récentes montrent un excellent niveau de bien-être. Continuez vos bonnes habitudes ! Maintenir un rythme de sommeil régulier vous aidera à prolonger cette énergie positive.";
        } elseif ($avgScore > 50) {
            return "Vous avez un niveau de bien-être modéré avec quelques fluctuations. N'oubliez pas de prendre des pauses régulières et d'essayer nos exercices de respiration guidée quand vous vous sentez stressé(e).";
        } else {
            return "Il semble que vous traversiez une période plus difficile. Vos scores indiquent un besoin de repos et d'attention à vous-même. Nous vous suggérons d'en parler avec un proche ou de réserver une consultation avec l'un de nos professionnels.";
        }
    }
}
