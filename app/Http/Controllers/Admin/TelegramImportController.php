<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Setting;
use App\Models\TelegramRawImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramImportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch telegram settings
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Check if setup is required
        $setup_required = empty($settings['telegram_bot_token']) || empty($settings['telegram_channel_id']);

        try {
            $imports = \App\Models\TelegramRawImport::latest()
                ->paginate(20);
            
            $stats = [
                'total' => \App\Models\TelegramRawImport::count(),
                'pending' => \App\Models\TelegramRawImport::where('processing_status', 'pending')->count(),
                'processing' => \App\Models\TelegramRawImport::where('processing_status', 'processing')->count(),
                'completed' => \App\Models\TelegramRawImport::where('processing_status', 'completed')->count(),
                'failed' => \App\Models\TelegramRawImport::where('processing_status', 'failed')->count(),
            ];
        } catch (\Exception $e) {
            // Fallback to empty data if database issues
            $imports = new LengthAwarePaginator([], 0, 20);
            $stats = [
                'total' => 0,
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'failed' => 0,
            ];
        }

        return view('admin.telegram-imports.index', compact('imports', 'stats', 'settings', 'setup_required'));
    }

    public function show($id)
    {
        $import = \App\Models\TelegramRawImport::findOrFail($id);
        
        return view('admin.telegram-imports.show', compact('import'));
    }

    public function import(Request $request)
    {
        // Simulate a longer import process for the progress bar
        sleep(2);

        try {
            // 1. Fetch settings
            $botTokenSetting = Setting::where('key', 'telegram_bot_token')->first();
            $channelIdSetting = Setting::where('key', 'telegram_channel_id')->first();
            $lastUpdateIdSetting = Setting::where('key', 'telegram_last_message_id')->first();

            $botToken = $botTokenSetting ? $botTokenSetting->value : null;
            $channelId = $channelIdSetting ? $channelIdSetting->value : null;
            $lastUpdateId = $lastUpdateIdSetting ? $lastUpdateIdSetting->value : null;

            // 2. Validate settings
            if (!$botToken || !$channelId) {
                return redirect()->route('admin.telegram-imports.index')->with('error', 'Telegram Bot Token and Channel ID must be set in settings.');
            }

            // 3. Construct Telegram API URL
            $offset = $lastUpdateId ? (int)$lastUpdateId + 1 : 0;
            $url = "https://api.telegram.org/bot{$botToken}/getUpdates?chat_id={$channelId}&offset={$offset}";

            // 4. Make API call
            $response = Http::get($url);
            $data = $response->json();

            // 5. Process the response
            if (isset($data['ok']) && $data['ok'] === true && !empty($data['result'])) {
                $lastProcessedUpdateId = null;

                foreach ($data['result'] as $update) {
                    $message = $update['channel_post'] ?? null;

                    if ($message) {
                        $mediaType = 'text';
                        $caption = $message['text'] ?? ($message['caption'] ?? null);
                        $telegramMessageId = $message['message_id'];

                        if (isset($message['photo'])) {
                            $mediaType = 'photo';
                        } elseif (isset($message['video'])) {
                            $mediaType = 'video';
                        } elseif (isset($message['audio'])) {
                            $mediaType = 'audio';
                        }

                        TelegramRawImport::create([
                            'telegram_message_id' => $telegramMessageId,
                            'media_type' => $mediaType,
                            'caption' => $caption,
                            'raw_data' => json_encode($message),
                            'processing_status' => 'pending',
                        ]);
                    }
                    
                    $lastProcessedUpdateId = $update['update_id'];
                }

                // 6. Update last message ID
                if ($lastProcessedUpdateId && $lastUpdateIdSetting) {
                    $lastUpdateIdSetting->update(['value' => $lastProcessedUpdateId]);
                } elseif ($lastProcessedUpdateId) {
                    Setting::create(['key' => 'telegram_last_message_id', 'value' => $lastProcessedUpdateId]);
                }
                
                $count = count($data['result']);
                return redirect()->route('admin.telegram-imports.index')->with('success', "{$count} new messages imported successfully.");
            } elseif (isset($data['ok']) && $data['ok'] === false) {
                Log::error('Telegram API Error: ' . ($data['description'] ?? 'Unknown error'));
                return redirect()->route('admin.telegram-imports.index')->with('error', 'Telegram API Error: ' . ($data['description'] ?? 'Unknown error'));
            } else {
                 return redirect()->route('admin.telegram-imports.index')->with('info', 'No new messages to import.');
            }

        } catch (\Exception $e) {
            Log::error('Telegram Import Failed: ' . $e->getMessage());
            return redirect()->route('admin.telegram-imports.index')->with('error', 'An error occurred during the Telegram import: ' . $e->getMessage());
        }
    }
}
