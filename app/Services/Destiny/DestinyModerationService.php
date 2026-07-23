<?php

namespace App\Services\Destiny;

class DestinyModerationService
{
    /**
     * Check if a message is safe (not toxic or spam).
     */
    public function isMessageSafe(string $message, &$isCrisis = false): bool
    {
        $aiService = new \App\Services\AiModerationService();
        $result = $aiService->analyzeMessage($message);
        
        $isCrisis = $result['is_crisis'];
        
        // Block message if not safe, OR if it's spam
        if (!$result['is_safe'] || $this->isSpam($message)) {
            return false;
        }

        return true;
    }

    private function isSpam(string $message): bool
    {
        // Placeholder for spam detection
        // Example: block messages with multiple URLs or repetitive characters
        return preg_match('/(http[s]?:\/\/[^\s]+.*){2,}/', $message) || preg_match('/(.)\1{5,}/', $message);
    }
}
