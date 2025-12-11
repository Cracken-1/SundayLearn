<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramWebhookInfo extends Command
{
    protected $signature = 'telegram:webhook-info';
    protected $description = 'Get current webhook information';

    public function handle(): int
    {
        $telegramService = new TelegramService();
        $result = $telegramService->getWebhookInfo();

        if ($result['ok']) {
            $info = $result['result'];
            
            $this->info("Webhook Information:");
            $this->line("URL: " . ($info['url'] ?: 'Not set'));
            $this->line("Has custom certificate: " . ($info['has_custom_certificate'] ? 'Yes' : 'No'));
            $this->line("Pending update count: " . $info['pending_update_count']);
            
            if (isset($info['last_error_date'])) {
                $this->warn("Last error date: " . date('Y-m-d H:i:s', $info['last_error_date']));
                $this->warn("Last error message: " . $info['last_error_message']);
            }
            
            if (isset($info['max_connections'])) {
                $this->line("Max connections: " . $info['max_connections']);
            }
        } else {
            $this->error("Failed to get webhook info: " . $result['description']);
            return 1;
        }

        return 0;
    }
}