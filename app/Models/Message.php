<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'content',
        'parent_id',
        'read_at',
        'deleted_by_sender',
        'deleted_by_receiver'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean'
    ];

    protected $with = ['sender'];

    // Relationships
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->where('deleted_by_sender', false)
              ->orWhere(function ($q) use ($userId) {
                  $q->where('receiver_id', $userId)
                    ->where('deleted_by_receiver', false);
              });
        });
    }

    public function scopeThread(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
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

    public function deleteForUser(int $userId): void
    {
        if ($this->sender_id === $userId) {
            $this->deleted_by_sender = true;
        } elseif ($this->receiver_id === $userId) {
            $this->deleted_by_receiver = true;
        }
        $this->save();

        // If both users deleted the message, we can physically delete it
        if ($this->deleted_by_sender && $this->deleted_by_receiver) {
            $this->delete();
        }
    }

    public function restore(int $userId): void
    {
        if ($this->sender_id === $userId) {
            $this->deleted_by_sender = false;
        } elseif ($this->receiver_id === $userId) {
            $this->deleted_by_receiver = false;
        }
        $this->save();
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function isRead(): bool
    {
        return !$this->isUnread();
    }

    public function canBeAccessedBy(int $userId): bool
    {
        return ($this->sender_id === $userId && !$this->deleted_by_sender) ||
               ($this->receiver_id === $userId && !$this->deleted_by_receiver);
    }

    public function isDeletedBy(int $userId): bool
    {
        return ($this->sender_id === $userId && $this->deleted_by_sender) ||
               ($this->receiver_id === $userId && $this->deleted_by_receiver);
    }

    public function hasReplies(): bool
    {
        return $this->replies()->count() > 0;
    }

    // Events
    protected static function booted()
    {
        static::created(function ($message) {
            // Create notification for receiver (commented out for now)
            // if ($message->receiver) {
            //     Notification::create([
            //         'user_id' => $message->receiver_id,
            //         'type' => Notification::TYPE_NEW_MESSAGE,
            //         'notifiable_type' => Message::class,
            //         'notifiable_id' => $message->id,
            //         'data' => [
            //             'message_id' => $message->id,
            //             'sender_name' => $message->sender->name,
            //             'subject' => $message->subject
            //         ]
            //     ]);
            // }
        });
    }
}
