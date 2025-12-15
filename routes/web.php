<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BlogController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('lessons')->group(function () {
    Route::get('/', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/{id}', [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/{lesson}/download/{attachment}', [LessonController::class, 'downloadAttachment'])->name('lessons.download-attachment');
});

Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/{id}', [BlogController::class, 'show'])->name('blog.show');
});

Route::get('/about', [App\Http\Controllers\PageController::class, 'about'])->name('about');

// Resources routes
Route::get('/resources', [App\Http\Controllers\ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{id}/download', [App\Http\Controllers\ResourceController::class, 'download'])->name('resources.download');


// Admin authentication routes (outside middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::get('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    // Emergency session clear route
    Route::get('/clear-session', function() {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Session cleared successfully.');
    })->name('clear-session');
    

});

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware(['admin.auth', 'prevent.back.history'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Lessons Management
    Route::resource('lessons', App\Http\Controllers\Admin\LessonController::class)->middleware('large.uploads');
    Route::delete('/lessons/{lesson}/attachments/{index}', [App\Http\Controllers\Admin\LessonController::class, 'removeAttachment'])->name('lessons.remove-attachment');
    
    // Blog Management
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    
    // Telegram Imports
    Route::get('/telegram-imports', [App\Http\Controllers\Admin\TelegramImportController::class, 'index'])->name('telegram-imports.index');
    Route::get('/telegram-imports/{import}', [App\Http\Controllers\Admin\TelegramImportController::class, 'show'])->name('telegram-imports.show');
    
    // Analytics
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    
    // Content Management
    Route::get('/content', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
    Route::post('/content/bulk-actions', [App\Http\Controllers\Admin\ContentController::class, 'bulkActions'])->name('content.bulk-actions');
    Route::post('/content/cleanup', [App\Http\Controllers\Admin\ContentController::class, 'cleanup'])->name('content.cleanup');
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::get('/account-settings', [App\Http\Controllers\Admin\UserController::class, 'accountSettings'])->name('users.account-settings');
    Route::put('/account-settings', [App\Http\Controllers\Admin\UserController::class, 'updateAccountSettings'])->name('users.account-settings.update');
    
    // Backup Management
    Route::get('/backups', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backups.create');
    Route::get('/backups/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backups.destroy');
    
    // Events Management
    Route::resource('events', App\Http\Controllers\Admin\EventController::class);
    
    // Teaching Tips Management
    Route::resource('teaching-tips', App\Http\Controllers\Admin\TeachingTipController::class);
    
    // Resources Management
    Route::resource('resources', App\Http\Controllers\Admin\ResourceController::class)->middleware('large.uploads');
    
    // Newsletter Management
    Route::get('/newsletters', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::get('/newsletters/export', [App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletters.export');
    Route::delete('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'updateEditorSettings'])->name('settings.update');
    Route::post('/settings/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimizeApp'])->name('settings.optimize');
    Route::post('/settings/migrate', [App\Http\Controllers\Admin\SettingsController::class, 'runMigrations'])->name('settings.migrate');
    Route::post('/settings/telegram', [App\Http\Controllers\Admin\SettingsController::class, 'updateTelegramSettings'])->name('settings.telegram');
    Route::post('/settings/cleanup', [App\Http\Controllers\Admin\SettingsController::class, 'cleanupStorage'])->name('settings.cleanup');
});