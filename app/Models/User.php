<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use App\Models\JobOffer;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // User types
    const TYPE_COMPANY = 'company';
    const TYPE_UNEMPLOYED = 'unemployed';
    const TYPE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone',
        'avatar',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed'
    ];

    protected $appends = [
        'profile_completed',
        'avatar_url'
    ];

    // Relationships
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function unemployed(): HasOne
    {
        return $this->hasOne(Unemployed::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    // Role checks
    public function isCompany(): bool
    {
        return $this->type === self::TYPE_COMPANY;
    }

    public function isUnemployed(): bool
    {
        return $this->type === self::TYPE_UNEMPLOYED;
    }

    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    // Attribute accessors
    public function getProfileCompletedAttribute(): bool
    {
        if ($this->isCompany()) {
            return $this->company !== null;
        }
        return $this->unemployed !== null;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    // Helper methods
    public function canApplyTo(JobOffer $jobOffer): bool
    {
        if (!$this->isUnemployed()) {
            return false;
        }

        if (!$jobOffer->status) {
            return false;
        }

        return !$this->unemployed->hasAppliedTo($jobOffer);
    }

    public function canPostJobOffer(): bool
    {
        return $this->isCompany() && $this->company !== null;
    }

    public function getPreferences(): UserPreference
    {
        return $this->preference ?? $this->preference()->create([
            'email_notifications' => true,
            'push_notifications' => true,
            'public_profile' => true,
            'language' => 'es',
            'timezone' => 'America/Mexico_City'
        ]);
    }

    public function updateLastLogin(): void
    {
        $this->last_login_at = now();
        $this->save();
    }

    // Query scopes
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Events
    protected static function booted()
    {
        static::created(function ($user) {
            $user->getPreferences();
        });
    }
}
