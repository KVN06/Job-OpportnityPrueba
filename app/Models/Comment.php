<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'parent_id',
        'commentable_type',
        'commentable_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $with = ['user'];

    // Comment status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with('user')
                    ->orderBy('created_at', 'asc');
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeParentOnly(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithReplies(Builder $query): Builder
    {
        return $query->with(['replies' => function($query) {
            $query->approved();
        }]);
    }

    // Helper Methods
    public function approve(): void
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function hasReplies(): bool
    {
        return $this->replies()->count() > 0;
    }

    public function canBeModifiedBy(User $user): bool
    {
        return $user->id === $this->user_id || $user->isAdmin();
    }

    // Events
    protected static function booted()
    {
        static::created(function ($comment) {
            // Notify the owner of the commentable item
            if ($comment->commentable && method_exists($comment->commentable, 'user')) {
                $owner = $comment->commentable->user;
                if ($owner && $owner->id !== $comment->user_id) {
                    Notification::create([
                        'user_id' => $owner->id,
                        'type' => 'new_comment',
                        'notifiable_type' => get_class($comment->commentable),
                        'notifiable_id' => $comment->commentable->id,
                        'data' => [
                            'comment_id' => $comment->id,
                            'commenter_name' => $comment->user->name,
                            'content_preview' => str_limit($comment->content, 100)
                        ]
                    ]);
                }
            }
        });
    }
}
