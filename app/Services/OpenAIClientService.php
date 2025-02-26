<?php
namespace App\Services;

use App\Core\Config;
Use App\Services\Interfaces\IAClientInterface;

class OpenAIClientService implements IAClientInterface
{
    private string $apiKey;
    private string $url;

    public function __construct()
    {
        $this->apiKey = Config::get('OPENAI_API_KEY');
        $this->url = Config::get('OPENAI_API_URL');
    }

    public function send(string $promptText): string|array
    {
        // Datas
        $data = [
            "model" => "gpt-4o",
            "messages" => [
                ["role" => "user", "content" => $promptText]
            ]
        ];

        $ch = curl_init($this->url);

        // Options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: " . "Bearer " . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Retrieving the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error_msg = 'Erreur cURL : ' . curl_error($ch);
            curl_close($ch);
            return ['error' => $error_msg];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'error' => "HTTP Code: $httpCode",
                'response' => $response
            ];
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['choices'][0]['message']['content'])) {
            return $responseData['choices'][0]['message']['content'];
        }

        return ['error' => 'No reply from OpenAI'];
    }
}

