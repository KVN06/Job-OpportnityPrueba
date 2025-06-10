<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'description',
        'website',
        'location',
        'logo',
        'industry',
        'size',
        'founded_year',
        'contact_email',
        'contact_phone',
        'social_media',
        'benefits',
        'culture',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'size' => 'integer',
        'founded_year' => 'integer',
        'social_media' => 'array',
        'benefits' => 'array',
        'culture' => 'array'
    ];

    protected $appends = [
        'active_job_offers_count',
        'total_applications_count',
        'logo_url',
        'rating'
    ];

    // Company size ranges
    const SIZE_STARTUP = 'startup'; // 1-10
    const SIZE_SMALL = 'small'; // 11-50
    const SIZE_MEDIUM = 'medium'; // 51-200
    const SIZE_LARGE = 'large'; // 201-1000
    const SIZE_ENTERPRISE = 'enterprise'; // 1000+

    // Company status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_VERIFIED = 'verified';
    const STATUS_UNVERIFIED = 'unverified';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }

    public function reviews()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('type', 'review');
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeByIndustry($query, $industry)
    {
        return $query->where('industry', $industry);
    }

    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }

    public function scopeHiringNow($query)
    {
        return $query->whereHas('jobOffers', function($q) {
            $q->where('status', true)
              ->where('application_deadline', '>', now());
        });
    }

    // Attribute Accessors
    public function getActiveJobOffersCountAttribute(): int
    {
        return $this->jobOffers()->where('status', true)->count();
    }

    public function getTotalApplicationsCountAttribute(): int
    {
        return JobApplication::whereIn(
            'job_offer_id', 
            $this->jobOffers()->pluck('id')
        )->count();
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? Storage::url($this->logo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->company_name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0.0;
    }

    // Helper Methods
    public function getSizeRange(): string
    {
        if ($this->size <= 10) return self::SIZE_STARTUP;
        if ($this->size <= 50) return self::SIZE_SMALL;
        if ($this->size <= 200) return self::SIZE_MEDIUM;
        if ($this->size <= 1000) return self::SIZE_LARGE;
        return self::SIZE_ENTERPRISE;
    }

    public function verify(): void
    {
        $this->status = self::STATUS_VERIFIED;
        $this->save();
    }

    public function deactivate(): void
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    public function isHiring(): bool
    {
        return $this->active_job_offers_count > 0;
    }

    public function addBenefit(string $benefit): void
    {
        $benefits = $this->benefits ?? [];
        if (!in_array($benefit, $benefits)) {
            $benefits[] = $benefit;
            $this->benefits = $benefits;
            $this->save();
        }
    }

    public function updateSocialMedia(array $profiles): void
    {
        $this->social_media = array_merge($this->social_media ?? [], $profiles);
        $this->save();
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($company) {
            $company->slug = str($company->company_name)->slug();
            $company->status = $company->status ?? self::STATUS_UNVERIFIED;
        });

        static::saving(function ($company) {
            if ($company->isDirty('company_name')) {
                $company->slug = str($company->company_name)->slug();
            }
        });

        static::deleting(function ($company) {
            if ($company->logo) {
                Storage::delete($company->logo);
            }
        });
    }
}
