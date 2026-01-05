<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'telegram_bot_token',
                'value' => null,
            ],
            [
                'key' => 'telegram_channel_id',
                'value' => null,
            ],
            [
                'key' => 'telegram_webhook_url',
                'value' => null,
            ],
            [
                'key' => 'telegram_last_message_id',
                'value' => null,
            ],
        ]);
    }
}
