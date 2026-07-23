<?php

namespace App\Services\Wellness;

use App\Services\Wellness\Contracts\LlmProviderInterface;

class AiAssistantService
{
    public function __construct(
        private readonly LlmProviderInterface $llmProvider
    ) {}

    public function generateSupportResponse(string $userMessage): string
    {
        // Wrap prompt with system instructions to ensure it remains a supportive wellness coach
        $systemPrompt = "You are an empathetic wellness assistant for the AF IYI platform. Keep your response in French, friendly, warm, and brief. Never diagnose clinical conditions. Suggest breathing exercises, personal journaling, or peer discussion if helpful. User message: " . $userMessage;

        return $this->llmProvider->generateResponse($systemPrompt);
    }
}
