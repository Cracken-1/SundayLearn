<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramRawImport extends Model
{
    protected $fillable = [
        'telegram_message_id',
        'telegram_file_id',
        'media_type',
        'caption',
        'telegram_data',
        'file_url',
        'file_path',
        'processing_status',
        'processing_notes',
        'lesson_id',
    ];

    protected $casts = [
        'telegram_data' => 'array',
    ];

    // Processing status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Media type constants
    const MEDIA_PHOTO = 'photo';
    const MEDIA_VIDEO = 'video';
    const MEDIA_AUDIO = 'audio';
    const MEDIA_DOCUMENT = 'document';

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopePending($query)
    {
        return $query->where('processing_status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('processing_status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('processing_status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('processing_status', self::STATUS_FAILED);
    }

    public function markAsProcessing(): void
    {
        $this->update(['processing_status' => self::STATUS_PROCESSING]);
    }

    public function markAsCompleted(?int $lessonId = null): void
    {
        $this->update([
            'processing_status' => self::STATUS_COMPLETED,
            'lesson_id' => $lessonId,
        ]);
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'processing_status' => self::STATUS_FAILED,
            'processing_notes' => $reason,
        ]);
    }
}