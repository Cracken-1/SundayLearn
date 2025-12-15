<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->botToken = config('telegram.bot_token');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function getFileUrl(string $fileId): ?string
    {
        try {
            $response = $this->makeRequest('getFile', ['file_id' => $fileId]);

            if (!($response['ok'] ?? false)) {
                Log::error('Telegram API returned error for getFile', [
                    'file_id' => $fileId,
                    'error' => $response,
                ]);
                return null;
            }

            $filePath = $response['result']['file_path'];
            return "https://api.telegram.org/file/bot{$this->botToken}/{$filePath}";

        } catch (\Exception $e) {
            Log::error('Exception getting file URL from Telegram', [
                'file_id' => $fileId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function setWebhook(string $url, ?string $secretToken = null): array
    {
        $params = ['url' => $url];
        
        if ($secretToken) {
            $params['secret_token'] = $secretToken;
        }

        return $this->makeRequest('setWebhook', $params);
    }

    public function getWebhookInfo(): array
    {
        return $this->makeRequest('getWebhookInfo');
    }

    public function deleteWebhook(): array
    {
        return $this->makeRequest('deleteWebhook');
    }

    public function getUpdates(int $offset = 0, int $limit = 100): array
    {
        return $this->makeRequest('getUpdates', [
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    public function sendMessage(string $chatId, string $text): array
    {
        return $this->makeRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }

    private function makeRequest(string $method, array $params = []): array
    {
        $url = "{$this->baseUrl}/{$method}";
        
        // Try cURL first
        if (function_exists('curl_init')) {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Try disabling SSL verify for broken hosts
            
            if (!empty($params)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }

            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($result !== false && !empty($result)) {
                $decoded = json_decode($result, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
        }

        // Fallback to file_get_contents if cURL failed or isn't available
        try {
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => empty($params) ? 'GET' : 'POST',
                    'content' => empty($params) ? null : http_build_query($params),
                    'timeout' => 30,
                    'ignore_errors' => true // Fetch content even on 4xx/5xx status
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ];
            
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            if ($result !== false) {
                return json_decode($result, true) ?? [
                    'ok' => false,
                    'description' => 'Invalid JSON from fallback',
                    'error_code' => 500
                ];
            }
        } catch (\Exception $e) {
            // Ignore fallback error and return original curl error if it existed
        }

        return [
            'ok' => false,
            'description' => "Connection failed" . (isset($error) ? ": {$error}" : ""),
            'error_code' => 0
        ];
    }
}