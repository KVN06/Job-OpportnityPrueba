<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unemployed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profession',
        'experience',
        'experience_years',
        'experience_level',
        'location',
        'skills',
        'education',
        'cv',
        'remote_work',
        'expected_salary',
        'bio',
        'availability'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'remote_work' => 'boolean',
        'expected_salary' => 'decimal:2',
        'experience_years' => 'integer'
    ];

    protected $appends = [
        'full_name',
        'active_applications_count',
        'profile_completion'
    ];

    // Constants for availability
    const AVAILABILITY_IMMEDIATE = 'immediate';
    const AVAILABILITY_TWO_WEEKS = 'two_weeks';
    const AVAILABILITY_ONE_MONTH = 'one_month';
    const AVAILABILITY_NEGOTIABLE = 'negotiable';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'favorite_offers', 'unemployed_id', 'job_offer_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function portfolio()
    {
        return $this->hasOne(Portfolio::class);
    }

    // Attribute Accessors
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }

    public function getActiveApplicationsCountAttribute()
    {
        return $this->jobApplications()->count();
    }

    public function getProfileCompletionAttribute()
    {
        $fields = ['profession', 'experience', 'location', 'skills', 'education', 'bio'];
        $completedFields = collect($fields)->filter(fn($field) => !empty($this->$field))->count();
        return round(($completedFields / count($fields)) * 100);
    }

    // Query Scopes
    public function scopeByProfession($query, $profession)
    {
        return $query->where('profession', 'LIKE', "%{$profession}%");
    }

    public function scopeWithHighCompletion($query, $percentage = 80)
    {
        return $query->whereNotNull(['profession', 'experience', 'location', 'skills']);
    }

    public function scopeAvailable($query, $availability)
    {
        return $query->where('availability', $availability);
    }

    // Helper Methods
    public function hasAppliedTo(JobOffer $jobOffer)
    {
        return $this->jobApplications()->where('job_offer_id', $jobOffer->id)->exists();
    }

    public function hasFavorited(JobOffer $jobOffer)
    {
        return $this->favoriteOffers()->where('job_offer_id', $jobOffer->id)->exists();
    }

    public function matchesJobRequirements(JobOffer $jobOffer)
    {
        // Simple matching algorithm based on skills
        $jobSkills = collect($jobOffer->required_skills ?? []);
        $userSkills = collect($this->skills ?? []);
        
        return $userSkills->intersect($jobSkills)->count() > 0;
    }
}
