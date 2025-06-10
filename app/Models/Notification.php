<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Notification types
    const TYPE_JOB_APPLICATION = 'job_application';
    const TYPE_JOB_STATUS = 'job_status';
    const TYPE_NEW_MESSAGE = 'new_message';
    const TYPE_APPLICATION_STATUS = 'application_status';
    const TYPE_NEW_JOB_MATCH = 'new_job_match';
    const TYPE_PROFILE_VIEW = 'profile_view';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    // Helper Methods
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->read_at = now();
            $this->save();
        }
    }

    public function markAsUnread(): void
    {
        if ($this->read_at) {
            $this->read_at = null;
            $this->save();
        }
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function isUnread(): bool
    {
        return !$this->isRead();
    }

    // Format notification data based on type
    public function getFormattedDataAttribute(): array
    {
        $data = $this->data;

        switch ($this->type) {
            case self::TYPE_JOB_APPLICATION:
                return [
                    'title' => 'Nueva postulación',
                    'message' => $data['applicant_name'] . ' se ha postulado a ' . $data['job_title'],
                    'link' => route('job-applications.show', $data['application_id'])
                ];

            case self::TYPE_JOB_STATUS:
                return [
                    'title' => 'Estado de oferta actualizado',
                    'message' => 'La oferta "' . $data['job_title'] . '" ha sido ' . $data['status'],
                    'link' => route('job-offers.show', $data['job_id'])
                ];

            case self::TYPE_NEW_MESSAGE:
                return [
                    'title' => 'Nuevo mensaje',
                    'message' => 'Has recibido un mensaje de ' . $data['sender_name'],
                    'link' => route('messages.show', $data['message_id'])
                ];

            default:
                return $data;
        }
    }
}
