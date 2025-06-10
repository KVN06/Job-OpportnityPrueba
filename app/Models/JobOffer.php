<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\Comment;
use App\Models\FavoriteOffer;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobOfferCategory;

class JobOffer extends Model
{
    use HasFactory;

    // Constants for offer types
    const TYPE_CONTRACT = 'contract';
    const TYPE_CLASSIFIED = 'classified';

    // Constants for contract types
    const CONTRACT_FULL_TIME = 'tiempo_completo';
    const CONTRACT_PART_TIME = 'medio_tiempo';
    const CONTRACT_PROJECT = 'proyecto';
    const CONTRACT_INTERNSHIP = 'practicas';

    // Constants for experience levels
    const LEVEL_JUNIOR = 'junior';
    const LEVEL_MID = 'medio';
    const LEVEL_SENIOR = 'senior';
    const LEVEL_LEAD = 'lead';

    // Constants for status
    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'salary',
        'location',
        'geolocation',
        'offer_type',
        'contract_type',
        'experience_level',
        'application_deadline',
        'remote_work',
        'required_skills',
        'benefits',
        'status'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'status' => 'boolean',
        'remote_work' => 'boolean',
        'geolocation' => 'array',
        'required_skills' => 'array',
        'benefits' => 'array',
        'application_deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'salary_formatted',
        'translated_offer_type',
        'status_text',
        'contract_type_text',
        'experience_level_text'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoriteOffers()
    {
        return $this->hasMany(FavoriteOffer::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'job_offer_categories');
    }

    public function favoriteUnemployed()
    {
        return $this->belongsToMany(Unemployed::class, 'favorite_offers', 'job_offer_id', 'unemployed_id');
    }

    // Attribute Accessors
    public function getContractTypeTextAttribute()
    {
        $types = [
            self::CONTRACT_FULL_TIME => 'Tiempo Completo',
            self::CONTRACT_PART_TIME => 'Medio Tiempo',
            self::CONTRACT_PROJECT => 'Proyecto',
            self::CONTRACT_INTERNSHIP => 'Prácticas'
        ];
        return $types[$this->contract_type] ?? ucfirst($this->contract_type);
    }

    public function getExperienceLevelTextAttribute()
    {
        $levels = [
            self::LEVEL_JUNIOR => 'Junior',
            self::LEVEL_MID => 'Medio',
            self::LEVEL_SENIOR => 'Senior',
            self::LEVEL_LEAD => 'Lead'
        ];
        return $levels[$this->experience_level] ?? ucfirst($this->experience_level);
    }

    public function getSalaryFormattedAttribute()
    {
        return $this->salary ? '$ ' . number_format($this->salary, 2) : 'No especificado';
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'Activa' : 'Inactiva';
    }

    public function getTranslatedOfferTypeAttribute()
    {
        $types = [
            self::TYPE_CONTRACT => 'Contrato',
            self::TYPE_CLASSIFIED => 'Clasificado'
        ];
        return $types[$this->offer_type] ?? ucfirst($this->offer_type);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Devuelve true si la oferta está activa
    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    // Verifica si el usuario puede editar la oferta
    public function canBeEditedBy($user): bool
    {
        if (!$user) return false;
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if ($user->isCompany() && $user->company && $user->company->id === $this->company_id) {
            return true;
        }
        return false;
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('offer_type', $type);
    }

    public function scopeByExperienceLevel($query, $level)
    {
        return $query->where('experience_level', $level);
    }
}
