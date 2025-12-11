<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramDeleteWebhook extends Command
{
    protected $signature = 'telegram:delete-webhook';
    protected $description = 'Delete the current webhook';

    public function handle(): int
    {
        $telegramService = new TelegramService();
        $result = $telegramService->deleteWebhook();

        if ($result['ok']) {
            $this->info("Webhook deleted successfully");
        } else {
            $this->error("Failed to delete webhook: " . $result['description']);
            return 1;
        }

        return 0;
    }
}