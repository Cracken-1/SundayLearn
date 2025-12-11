<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabase extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test database connection';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
            
            // Test if we can run a simple query
            $result = DB::select('SELECT 1 as test');
            if ($result && $result[0]->test == 1) {
                $this->info('✓ Database queries working');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}