<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnection extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test database connection to Supabase';

    public function handle()
    {
        $this->info('Testing database connection...');
        $this->newLine();

        try {
            // Test connection
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful!');
            
            // Get database info
            $dbName = DB::connection()->getDatabaseName();
            $this->info("✓ Connected to database: {$dbName}");
            
            // Test query
            $result = DB::select('SELECT version()');
            $version = $result[0]->version ?? 'Unknown';
            $this->info("✓ PostgreSQL version: {$version}");
            
            $this->newLine();
            
            // Check tables
            $tables = DB::select("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_type = 'BASE TABLE'
                ORDER BY table_name
            ");
            
            if (empty($tables)) {
                $this->warn('No tables found. Run migrations: php artisan migrate');
            } else {
                $this->info('✓ Found ' . count($tables) . ' tables:');
                foreach ($tables as $table) {
                    $this->line("  - {$table->table_name}");
                }
            }
            
            $this->newLine();
            $this->info('Database is ready to use! 🎉');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Please check your .env configuration:');
            $this->line('  - DB_HOST');
            $this->line('  - DB_DATABASE');
            $this->line('  - DB_USERNAME');
            $this->line('  - DB_PASSWORD');
            
            return 1;
        }
    }
}
