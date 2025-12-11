<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTelegramUpdate;
use App\Models\TelegramRawImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        // Validate webhook secret
        $expectedSecret = config('telegram.webhook_secret');
        $providedSecret = $request->header('X-Telegram-Bot-Api-Secret-Token');

        if (!$expectedSecret || $providedSecret !== $expectedSecret) {
            Log::warning('Telegram webhook: Invalid secret token', [
                'expected' => $expectedSecret ? 'set' : 'not set',
                'provided' => $providedSecret ? 'provided' : 'not provided',
            ]);
            return response('Unauthorized', 401);
        }

        $update = $request->all();
        
        Log::info('Telegram webhook received', [
            'update_id' => $update['update_id'] ?? null,
            'message_id' => $update['message']['message_id'] ?? null,
        ]);

        // Check if this is a channel post or message
        $message = $update['channel_post'] ?? $update['message'] ?? null;
        
        if (!$message) {
            Log::info('Telegram webhook: No message or channel_post found');
            return response('OK', 200);
        }

        // Check if message is from our configured channel
        $channelId = config('telegram.channel_id');
        $messageChannelId = $message['chat']['id'] ?? null;
        
        if ($channelId && $messageChannelId != $channelId) {
            Log::info('Telegram webhook: Message not from configured channel', [
                'expected_channel' => $channelId,
                'message_channel' => $messageChannelId,
            ]);
            return response('OK', 200);
        }

        // Check if we already processed this message
        $messageId = $message['message_id'];
        $existingImport = TelegramRawImport::where('telegram_message_id', $messageId)->first();
        
        if ($existingImport) {
            Log::info('Telegram webhook: Message already processed', [
                'message_id' => $messageId,
            ]);
            return response('OK', 200);
        }

        // Dispatch job to process the update
        ProcessTelegramUpdate::dispatch($update);

        return response('OK', 200);
    }
}