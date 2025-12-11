<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url} {--secret=}';
    protected $description = 'Set the Telegram webhook URL';

    public function handle(): int
    {
        $url = $this->argument('url');
        $secret = $this->option('secret') ?: config('telegram.webhook_secret');

        $telegramService = new TelegramService();
        $result = $telegramService->setWebhook($url, $secret);

        if ($result['ok']) {
            $this->info("Webhook set successfully to: {$url}");
            if ($secret) {
                $this->info("Secret token configured");
            }
        } else {
            $this->error("Failed to set webhook: " . $result['description']);
            return 1;
        }

        return 0;
    }
}