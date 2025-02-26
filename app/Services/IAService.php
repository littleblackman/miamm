<?php

namespace App\Services;

Use App\Services\OpenAIClientService;

class IAService
{
    private OpenAIClientService $client;

    public function __construct()
    {
        $this->client = new OpenAIClientService();
    }

    public function send(string $promptText): string|array
    {
        return $this->client->send($promptText);
    }
}
