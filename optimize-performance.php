<?php
/**
 * Performance Optimization Script for SundayLearn
 * 
 * This script optimizes the application for better performance on InfinityFree
 * 
 * Usage: php optimize-performance.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceOptimizer
{
    public function run()
    {
        echo "🚀 Starting Performance Optimization...\n";
        echo "=====================================\n";

        try {
            $this->clearCaches();
            $this->optimizeApplication();
            $this->optimizeDatabase();
            $this->createOptimizedViews();
            
            echo "\n✅ Performance optimization completed successfully!\n";
            echo "=====================================\n";
            echo "🎯 Optimizations applied:\n";
            echo "   - Cleared all caches\n";
            echo "   - Optimized configuration\n";
            echo "   - Cached routes and views\n";
            echo "   - Optimized database queries\n";
            echo "   - Created performance indexes\n";
            echo "\n📈 Expected improvements:\n";
            echo "   - Faster page load times\n";
            echo "   - Reduced server load\n";
            echo "   - Better user experience\n";

        } catch (Exception $e) {
            echo "\n❌ Error during optimization: " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    private function clearCaches()
    {
        echo "🧹 Clearing caches...\n";
        
        try {
            Artisan::call('cache:clear');
            echo "   ✅ Application cache cleared\n";
            
            Artisan::call('config:clear');
            echo "   ✅ Configuration cache cleared\n";
            
            Artisan::call('route:clear');
            echo "   ✅ Route cache cleared\n";
            
            Artisan::call('view:clear');
            echo "   ✅ View cache cleared\n";
            
        } catch (Exception $e) {
            echo "   ⚠️ Cache clearing warning: " . $e->getMessage() . "\n";
        }
    }

    private function optimizeApplication()
    {
        echo "\n⚡ Optimizing application...\n";
        
        try {
            Artisan::call('config:cache');
            echo "   ✅ Configuration cached\n";
            
            Artisan::call('route:cache');
            echo "   ✅ Routes cached\n";
            
            Artisan::call('view:cache');
            echo "   ✅ Views cached\n";
            
            Artisan::call('optimize');
            echo "   ✅ Application optimized\n";
            
        } catch (Exception $e) {
            echo "   ⚠️ Optimization warning: " . $e->getMessage() . "\n";
        }
    }

    private function optimizeDatabase()
    {
        echo "\n🗄️ Optimizing database...\n";
        
        try {
            // Add indexes for better performance
            $this->addPerformanceIndexes();
            
            // Optimize tables
            $tables = ['lessons', 'blog_posts', 'analytics', 'resources'];
            foreach ($tables as $table) {
                try {
                    DB::statement("OPTIMIZE TABLE {$table}");
                    echo "   ✅ Optimized {$table} table\n";
                } catch (Exception $e) {
                    echo "   ⚠️ Could not optimize {$table}: " . $e->getMessage() . "\n";
                }
            }
            
        } catch (Exception $e) {
            echo "   ⚠️ Database optimization warning: " . $e->getMessage() . "\n";
        }
    }

    private function addPerformanceIndexes()
    {
        $indexes = [
            'lessons' => [
                'idx_lessons_status_published' => 'status, published_at',
                'idx_lessons_featured_status' => 'is_featured, status',
                'idx_lessons_category_status' => 'category, status'
            ],
            'blog_posts' => [
                'idx_blog_posts_status_published' => 'status, published_at',
                'idx_blog_posts_featured_status' => 'is_featured, status'
            ],
            'analytics' => [
                'idx_analytics_type_date' => 'event_type, created_at',
                'idx_analytics_category_date' => 'event_category, created_at'
            ],
            'resources' => [
                'idx_resources_type_featured' => 'type, is_featured',
                'idx_resources_age_type' => 'age_group, type'
            ]
        ];

        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $indexName => $columns) {
                try {
                    DB::statement("CREATE INDEX {$indexName} ON {$table} ({$columns})");
                    echo "   ✅ Added index {$indexName} to {$table}\n";
                } catch (Exception $e) {
                    // Index might already exist, ignore
                }
            }
        }
    }

    private function createOptimizedViews()
    {
        echo "\n📊 Creating optimized database views...\n";
        
        try {
            // Create view for published lessons with counts
            DB::statement("
                CREATE OR REPLACE VIEW published_lessons_view AS
                SELECT 
                    id, title, slug, excerpt, scripture, theme, age_group, 
                    duration, thumbnail, category, difficulty, is_featured,
                    views_count, published_at, created_at
                FROM lessons 
                WHERE status = 'published' 
                ORDER BY published_at DESC
            ");
            echo "   ✅ Created published_lessons_view\n";
            
            // Create view for featured content
            DB::statement("
                CREATE OR REPLACE VIEW featured_content_view AS
                SELECT 
                    'lesson' as type, id, title, slug, excerpt, 
                    thumbnail, created_at, views_count
                FROM lessons 
                WHERE status = 'published' AND is_featured = 1
                UNION ALL
                SELECT 
                    'blog' as type, id, title, slug, excerpt, 
                    image_url as thumbnail, created_at, views_count
                FROM blog_posts 
                WHERE status = 'published' AND is_featured = 1
                ORDER BY created_at DESC
            ");
            echo "   ✅ Created featured_content_view\n";
            
        } catch (Exception $e) {
            echo "   ⚠️ View creation warning: " . $e->getMessage() . "\n";
        }
    }
}

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Run the optimizer
$optimizer = new PerformanceOptimizer();
$optimizer->run();

echo "\n🎉 Your SundayLearn application is now optimized for production!\n";
echo "💡 For best results on InfinityFree:\n";
echo "   - Run this script after each deployment\n";
echo "   - Monitor your resource usage\n";
echo "   - Consider upgrading if you exceed limits\n";
echo "   - Use CDN for static assets when possible\n";