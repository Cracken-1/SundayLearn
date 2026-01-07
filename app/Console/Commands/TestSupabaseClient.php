<?php

namespace App\Console\Commands;

use App\Services\SupabaseStorageService;
use Illuminate\Console\Command;

class TestSupabaseClient extends Command
{
    protected $signature = 'supabase:test';
    protected $description = 'Test Supabase PHP client integration';

    public function handle(SupabaseStorageService $storage)
    {
        $this->info('Testing Supabase PHP Client Integration...');
        
        try {
            // Test client initialization
            $client = $storage->getClient();
            $this->info('✅ Supabase client initialized successfully');
            $this->info('Client class: ' . get_class($client));
            
            // Test storage client access
            $storageClient = $client->storage;
            $this->info('✅ Storage client accessible: ' . get_class($storageClient));
            
            // Test configuration
            $this->info('Configuration:');
            $this->line('- URL: ' . config('supabase.url', 'Not set'));
            $this->line('- Service Key: ' . (config('supabase.service_key') ? 'Set' : 'Not set'));
            $this->line('- Anon Key: ' . (config('supabase.anon_key') ? 'Set' : 'Not set'));
            
            $this->info('✅ Supabase PHP client is ready to use!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error testing Supabase client: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}