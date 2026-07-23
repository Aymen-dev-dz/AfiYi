<?php

namespace App\Services\Wellness\Contracts;

interface LlmProviderInterface
{
    public function generateResponse(string $prompt, array $options = []): string;
}
