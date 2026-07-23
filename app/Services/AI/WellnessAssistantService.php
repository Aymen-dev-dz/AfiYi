<?php

namespace App\Services\AI;

use App\Services\Wellness\AiAssistantService;

class WellnessAssistantService
{
    public function __construct(
        private readonly AiAssistantService $aiAssistant
    ) {}

    /**
     * Analyze text and delegate response generation to LLM Provider Interface.
     */
    public function analyzeAndRespond(string $text, array $history = []): string
    {
        return $this->aiAssistant->generateSupportResponse($text);
    }
}
