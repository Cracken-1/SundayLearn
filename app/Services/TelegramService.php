<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
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
            $response = Http::get("{$this->baseUrl}/getFile", [
                'file_id' => $fileId,
            ]);

            if (!$response->successful()) {
                Log::error('Failed to get file info from Telegram', [
                    'file_id' => $fileId,
                    'response' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            
            if (!$data['ok']) {
                Log::error('Telegram API returned error for getFile', [
                    'file_id' => $fileId,
                    'error' => $data,
                ]);
                return null;
            }

            $filePath = $data['result']['file_path'];
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

        $response = Http::post("{$this->baseUrl}/setWebhook", $params);
        
        return $response->json();
    }

    public function getWebhookInfo(): array
    {
        $response = Http::get("{$this->baseUrl}/getWebhookInfo");
        
        return $response->json();
    }

    public function deleteWebhook(): array
    {
        $response = Http::post("{$this->baseUrl}/deleteWebhook");
        
        return $response->json();
    }

    public function getUpdates(int $offset = 0, int $limit = 100): array
    {
        $response = Http::get("{$this->baseUrl}/getUpdates", [
            'offset' => $offset,
            'limit' => $limit,
        ]);
        
        return $response->json();
    }

    public function sendMessage(string $chatId, string $text): array
    {
        $response = Http::post("{$this->baseUrl}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
        
        return $response->json();
    }
}