<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LessonApiController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\BotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Telegram webhook route
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

// API routes for lesson management
Route::prefix('v1')->group(function () {
    Route::get('/lessons', [App\Http\Controllers\Api\LessonApiController::class, 'index']);
    Route::get('/lessons/{lesson}', [App\Http\Controllers\Api\LessonApiController::class, 'show']);
    Route::get('/stats', [App\Http\Controllers\Api\LessonApiController::class, 'stats']);
    
    // Telegram import management
    Route::get('/telegram/imports', [App\Http\Controllers\Api\LessonApiController::class, 'telegramImports']);
    Route::get('/telegram/imports/{import}', [App\Http\Controllers\Api\LessonApiController::class, 'telegramImportShow']);
});

// Public API routes for Telegram Bot
Route::prefix('v1')->group(function () {
    
    // Lessons API
    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonApiController::class, 'index']);
        Route::get('/{id}', [LessonApiController::class, 'show']);
        Route::get('/age/{ageGroup}', [LessonApiController::class, 'byAge']);
        Route::get('/topic/{topic}', [LessonApiController::class, 'byTopic']);
        Route::get('/search/{query}', [LessonApiController::class, 'search']);
        Route::get('/random', [LessonApiController::class, 'random']);
        Route::get('/featured', [LessonApiController::class, 'featured']);
    });

    // Blog API
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogApiController::class, 'index']);
        Route::get('/{id}', [BlogApiController::class, 'show']);
        Route::get('/latest', [BlogApiController::class, 'latest']);
    });

    // Bot-specific endpoints
    Route::prefix('bot')->group(function () {
        Route::get('/stats', [BotController::class, 'stats']);
        Route::get('/quick-lesson', [BotController::class, 'quickLesson']);
        Route::get('/lesson-of-the-day', [BotController::class, 'lessonOfTheDay']);
        Route::get('/age-groups', [BotController::class, 'ageGroups']);
        Route::get('/topics', [BotController::class, 'topics']);
        Route::post('/webhook', [BotController::class, 'webhook']);
        Route::get('/help', [BotController::class, 'help']);
    });

    // Resources API
    Route::prefix('resources')->group(function () {
        Route::get('/', [BotController::class, 'resources']);
        Route::get('/download/{type}', [BotController::class, 'downloadResource']);
    });
});