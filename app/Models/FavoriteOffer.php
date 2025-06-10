<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class FavoriteOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'unemployed_id',
        'job_offer_id',
        'notes',
        'notification_preferences'
    ];

    protected $casts = [
        'notification_preferences' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Default notification preferences
    const DEFAULT_NOTIFICATIONS = [
        'status_changes' => true,
        'deadline_reminders' => true,
        'similar_offers' => true
    ];

    // Relationships
    public function unemployed(): BelongsTo
    {
        return $this->belongsTo(Unemployed::class);
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    // Query Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereHas('jobOffer', function ($query) {
            $query->where('status', true);
        });
    }

    public function scopeByCategory(Builder $query, $category): Builder
    {
        return $query->whereHas('jobOffer.categories', function ($query) use ($category) {
            $query->where('categories.id', $category);
        });
    }

    public function scopeRecentlyAdded(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper Methods
    public function toggleNotification(string $type, bool $enabled = true): void
    {
        $preferences = $this->notification_preferences ?? self::DEFAULT_NOTIFICATIONS;
        $preferences[$type] = $enabled;
        $this->notification_preferences = $preferences;
        $this->save();
    }

    public function hasNotificationEnabled(string $type): bool
    {
        $preferences = $this->notification_preferences ?? self::DEFAULT_NOTIFICATIONS;
        return $preferences[$type] ?? false;
    }

    public function isActive(): bool
    {
        return $this->jobOffer->status;
    }

    public function hasDeadlineSoon(): bool
    {
        if (!$this->jobOffer->application_deadline) {
            return false;
        }

        return $this->jobOffer->application_deadline->diffInDays(now()) <= 3;
    }

    public function getMatchScore(): float
    {
        if (!$this->unemployed->skills || !$this->jobOffer->required_skills) {
            return 0.0;
        }

        $userSkills = collect($this->unemployed->skills);
        $requiredSkills = collect($this->jobOffer->required_skills);
        
        $matchingSkills = $userSkills->intersect($requiredSkills)->count();
        $totalRequired = $requiredSkills->count();
        
        return $totalRequired > 0 ? ($matchingSkills / $totalRequired) * 100 : 0.0;
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($favorite) {
            $favorite->notification_preferences = $favorite->notification_preferences ?? self::DEFAULT_NOTIFICATIONS;
        });

        static::created(function ($favorite) {
            // Create notification for the user
            Notification::create([
                'user_id' => $favorite->unemployed->user_id,
                'type' => 'favorite_added',
                'notifiable_type' => JobOffer::class,
                'notifiable_id' => $favorite->job_offer_id,
                'data' => [
                    'job_title' => $favorite->jobOffer->title,
                    'company_name' => $favorite->jobOffer->company->name
                ]
            ]);
        });
    }
}
