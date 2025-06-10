<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'public_profile',
        'job_preferences',
        'salary_range',
        'language',
        'timezone',
        'theme',
        'notification_frequency',
        'search_radius',
        // New notification settings
        'job_alerts',
        'message_notifications',
        'training_notifications',
        'application_notifications',
        // New privacy settings
        'show_email',
        'show_phone',
        'allow_messages',
        'show_activity'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'public_profile' => 'boolean',
        'job_preferences' => 'array',
        'salary_range' => 'array',
        'search_radius' => 'integer',
        // New notification settings
        'job_alerts' => 'boolean',
        'message_notifications' => 'boolean',
        'training_notifications' => 'boolean',
        'application_notifications' => 'boolean',
        // New privacy settings
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
        'allow_messages' => 'boolean',
        'show_activity' => 'boolean'
    ];

    // Notification frequency options
    const FREQUENCY_IMMEDIATELY = 'immediately';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';

    // Theme options
    const THEME_LIGHT = 'light';
    const THEME_DARK = 'dark';
    const THEME_SYSTEM = 'system';

    protected static function booted()
    {
        static::creating(function ($preference) {
            $preference->theme = $preference->theme ?? self::THEME_SYSTEM;
            $preference->notification_frequency = $preference->notification_frequency ?? self::FREQUENCY_DAILY;
            $preference->search_radius = $preference->search_radius ?? 50;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getJobPreferencesAttribute($value): array
    {
        return json_decode($value ?? '[]', true);
    }

    public function getSalaryRangeAttribute($value): array
    {
        return json_decode($value ?? '{"min": 0, "max": 0}', true);
    }

    public function shouldReceiveNotification(): bool
    {
        if (!$this->email_notifications && !$this->push_notifications) {
            return false;
        }

        if ($this->notification_frequency === self::FREQUENCY_IMMEDIATELY) {
            return true;
        }

        // For daily/weekly notifications, check last notification time
        $lastNotification = $this->user->notifications()->latest()->first();
        if (!$lastNotification) {
            return true;
        }

        $now = now();
        if ($this->notification_frequency === self::FREQUENCY_DAILY) {
            return $lastNotification->created_at->diffInDays($now) >= 1;
        }

        return $lastNotification->created_at->diffInWeeks($now) >= 1;
    }

    public function matchesJobOffer(JobOffer $jobOffer): bool
    {
        $preferences = $this->job_preferences;
        
        // Check location if search radius is set
        if ($this->search_radius > 0 && isset($preferences['location'])) {
            // Assuming we have a helper method to calculate distance
            $distance = $this->calculateDistance($preferences['location'], $jobOffer->location);
            if ($distance > $this->search_radius) {
                return false;
            }
        }

        // Check salary range
        $salaryRange = $this->salary_range;
        if ($salaryRange['min'] > 0 && $jobOffer->salary < $salaryRange['min']) {
            return false;
        }
        if ($salaryRange['max'] > 0 && $jobOffer->salary > $salaryRange['max']) {
            return false;
        }

        // Check job type preferences
        if (isset($preferences['job_types']) && !in_array($jobOffer->offer_type, $preferences['job_types'])) {
            return false;
        }

        return true;
    }

    private function calculateDistance($location1, $location2): float
    {
        // Implementation of distance calculation
        // Could use Google Maps API or similar service
        return 0; // Placeholder
    }
}
