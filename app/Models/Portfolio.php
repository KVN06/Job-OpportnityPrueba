<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'unemployed_id',
        'title',
        'description',
        'url',
        'image_path',
        'project_type',
        'technologies',
        'start_date',
        'end_date',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'technologies' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'duration',
        'image_url'
    ];

    // Project types
    const TYPE_PERSONAL = 'personal';
    const TYPE_PROFESSIONAL = 'professional';
    const TYPE_ACADEMIC = 'academic';
    const TYPE_OPEN_SOURCE = 'open_source';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    // Relationships
    public function unemployed(): BelongsTo
    {
        return $this->belongsTo(Unemployed::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByTechnology($query, $technology)
    {
        return $query->where('technologies', 'like', "%{$technology}%");
    }

    // Accessors
    public function getDurationAttribute(): ?string
    {
        if (!$this->start_date) {
            return null;
        }

        $end = $this->end_date ?? now();
        $months = $this->start_date->diffInMonths($end);
        $years = floor($months / 12);
        $remainingMonths = $months % 12;

        if ($years > 0) {
            return $years . ' año(s) ' . ($remainingMonths > 0 ? $remainingMonths . ' mes(es)' : '');
        }

        return $remainingMonths . ' mes(es)';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return Storage::url($this->image_path);
    }

    // Helper Methods
    public function addTechnology(string $technology): void
    {
        $technologies = $this->technologies ?? [];
        if (!in_array($technology, $technologies)) {
            $technologies[] = $technology;
            $this->technologies = $technologies;
            $this->save();
        }
    }

    public function removeTechnology(string $technology): void
    {
        if (!$this->technologies) {
            return;
        }

        $this->technologies = array_diff($this->technologies, [$technology]);
        $this->save();
    }

    public function publish(): void
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->save();
    }

    public function archive(): void
    {
        $this->status = self::STATUS_ARCHIVED;
        $this->save();
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function toggleFeatured(): void
    {
        $this->is_featured = !$this->is_featured;
        $this->save();
    }

    // Events
    protected static function booted()
    {
        static::deleting(function ($portfolio) {
            // Delete associated image if exists
            if ($portfolio->image_path) {
                Storage::delete($portfolio->image_path);
            }
        });
    }
}
