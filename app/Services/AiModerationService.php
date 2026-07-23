<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI;

class AiModerationService
{
    /**
     * Analyze a message for crisis indicators or community guideline violations.
     * 
     * @param string $message
     * @return array ['is_safe' => bool, 'is_crisis' => bool, 'reason' => string|null]
     */
    public function analyzeMessage(string $message): array
    {
        $apiKey = env('OPENAI_API_KEY');
        
        // Basic fallback heuristic if API key is not set or for quick bypass
        if (!$apiKey) {
            return $this->fallbackHeuristicAnalysis($message);
        }

        try {
            $client = OpenAI::client($apiKey);
            
            // First, run standard moderation API
            $response = $client->moderations()->create([
                'model' => 'text-moderation-latest',
                'input' => $message,
            ]);

            $result = $response->results[0];
            
            if ($result->flagged) {
                // If it's flagged for self-harm, it's a crisis
                $isCrisis = $result->categories->{'self-harm'} || $result->categories->{'self-harm/intent'} || $result->categories->{'self-harm/instructions'};
                
                return [
                    'is_safe' => false,
                    'is_crisis' => $isCrisis,
                    'reason' => 'Content violated safety guidelines.'
                ];
            }

            // If not flagged by moderation, we can do a quick heuristic for crisis keywords
            return $this->fallbackHeuristicAnalysis($message);

        } catch (\Exception $e) {
            Log::error('AI Moderation Service Error: ' . $e->getMessage());
            // Fallback to heuristic
            return $this->fallbackHeuristicAnalysis($message);
        }
    }

    private function fallbackHeuristicAnalysis(string $message): array
    {
        $messageLower = strtolower($message);
        
        // Crisis keywords
        $crisisKeywords = [
            'suicide', 'kill myself', 'want to die', 'end it all', 'give up on life', 
            'no reason to live', 'self-harm', 'hurt myself', 'cut myself', 'je veux mourir', 'me tuer'
        ];
        
        foreach ($crisisKeywords as $keyword) {
            if (str_contains($messageLower, $keyword)) {
                return [
                    'is_safe' => false,
                    'is_crisis' => true,
                    'reason' => 'Crisis keyword detected.'
                ];
            }
        }
        
        // Harassment/Toxicity keywords
        $toxicKeywords = [
            'fuck you', 'kill yourself', 'kys', 'bitch', 'whore', 'retard', 'connard', 'salope'
        ];
        
        foreach ($toxicKeywords as $keyword) {
            if (str_contains($messageLower, $keyword)) {
                return [
                    'is_safe' => false,
                    'is_crisis' => false,
                    'reason' => 'Toxic keyword detected.'
                ];
            }
        }
        
        return [
            'is_safe' => true,
            'is_crisis' => false,
            'reason' => null
        ];
    }
}
