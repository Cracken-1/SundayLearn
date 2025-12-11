<?php

namespace App\Jobs;

use App\Models\TelegramRawImport;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTelegramUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $update
    ) {}

    public function handle(): void
    {
        try {
            $message = $this->update['channel_post'] ?? $this->update['message'] ?? null;
            
            if (!$message) {
                Log::warning('ProcessTelegramUpdate: No message found in update');
                return;
            }

            $messageId = $message['message_id'];
            $caption = $message['caption'] ?? $message['text'] ?? '';
            
            // Determine media type and file ID
            $mediaType = null;
            $fileId = null;
            
            if (isset($message['photo'])) {
                $mediaType = TelegramRawImport::MEDIA_PHOTO;
                // Get the largest photo
                $photos = $message['photo'];
                $largestPhoto = end($photos);
                $fileId = $largestPhoto['file_id'];
            } elseif (isset($message['video'])) {
                $mediaType = TelegramRawImport::MEDIA_VIDEO;
                $fileId = $message['video']['file_id'];
            } elseif (isset($message['audio'])) {
                $mediaType = TelegramRawImport::MEDIA_AUDIO;
                $fileId = $message['audio']['file_id'];
            } elseif (isset($message['document'])) {
                $mediaType = TelegramRawImport::MEDIA_DOCUMENT;
                $fileId = $message['document']['file_id'];
            }

            // Create raw import record
            $rawImport = TelegramRawImport::create([
                'telegram_message_id' => $messageId,
                'telegram_file_id' => $fileId,
                'media_type' => $mediaType,
                'caption' => $caption,
                'telegram_data' => $this->update,
                'processing_status' => TelegramRawImport::STATUS_PENDING,
            ]);

            Log::info('Telegram update processed and stored', [
                'raw_import_id' => $rawImport->id,
                'message_id' => $messageId,
                'media_type' => $mediaType,
                'has_caption' => !empty($caption),
            ]);

            // If there's media, download it
            if ($fileId) {
                $telegramService = new TelegramService();
                $fileUrl = $telegramService->getFileUrl($fileId);
                
                if ($fileUrl) {
                    $rawImport->update(['file_url' => $fileUrl]);
                    Log::info('File URL retrieved for Telegram import', [
                        'raw_import_id' => $rawImport->id,
                        'file_url' => $fileUrl,
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Error processing Telegram update', [
                'error' => $e->getMessage(),
                'update' => $this->update,
            ]);
            throw $e;
        }
    }
}