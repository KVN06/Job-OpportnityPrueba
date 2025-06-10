<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link',
        'provider',
        'category',
        'level',
        'duration',
        'cost',
        'image_path',
        'start_date',
        'end_date',
        'max_participants',
        'status',
        'certification_available'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'cost' => 'decimal:2',
        'max_participants' => 'integer',
        'certification_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'is_full',
        'available_spots',
        'is_ongoing',
        'completion_rate'
    ];

    // Training status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_UPCOMING = 'upcoming';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Training level constants
    const LEVEL_BEGINNER = 'beginner';
    const LEVEL_INTERMEDIATE = 'intermediate';
    const LEVEL_ADVANCED = 'advanced';

    // Relationships
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'training_users')
                    ->withPivot(['status', 'progress', 'completed_at', 'certificate_id'])
                    ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingUser::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_UPCOMING, self::STATUS_ONGOING]);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->whereNull('max_participants')
              ->orWhereRaw('(SELECT COUNT(*) FROM training_users WHERE training_id = trainings.id) < max_participants');
        });
    }

    // Accessors
    public function getIsFullAttribute(): bool
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->participants()->count() >= $this->max_participants;
    }

    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_participants) {
            return null;
        }
        return max(0, $this->max_participants - $this->participants()->count());
    }

    public function getIsOngoingAttribute(): bool
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getCompletionRateAttribute(): float
    {
        $totalParticipants = $this->participants()->count();
        if ($totalParticipants === 0) {
            return 0;
        }

        $completedParticipants = $this->participants()
            ->wherePivot('status', 'completed')
            ->count();

        return round(($completedParticipants / $totalParticipants) * 100, 2);
    }

    // Helper Methods
    public function enroll(User $user): bool
    {
        if ($this->isParticipant($user) || $this->isFull()) {
            return false;
        }

        $this->participants()->attach($user->id, [
            'status' => 'enrolled',
            'progress' => 0
        ]);

        return true;
    }

    public function updateParticipantProgress(User $user, int $progress): bool
    {
        if (!$this->isParticipant($user)) {
            return false;
        }

        $enrollment = $this->enrollments()->where('user_id', $user->id)->first();
        $enrollment->progress = min(100, max(0, $progress));
        
        if ($progress >= 100) {
            $enrollment->status = 'completed';
            $enrollment->completed_at = now();
            if ($this->certification_available) {
                $enrollment->certificate_id = $this->generateCertificateId($user);
            }
        }

        $enrollment->save();
        return true;
    }

    public function isParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function isFull(): bool
    {
        return $this->is_full;
    }

    protected function generateCertificateId(User $user): string
    {
        return strtoupper(uniqid('CERT-' . $user->id . '-'));
    }

    // Events
    protected static function booted()
    {
        static::deleting(function ($training) {
            // Delete associated image if exists
            if ($training->image_path) {
                Storage::delete($training->image_path);
            }
        });
    }
}
