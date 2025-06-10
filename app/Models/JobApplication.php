<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Unemployed;
use App\Models\JobOffer;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'unemployed_id',
        'job_offer_id',
        'cover_letter',
        'resume_path',
        'status',
        'application_date',
        'notes'
    ];

    protected $casts = [
        'application_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $dates = [
        'application_date'
    ];

    // Application status constants
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_INTERVIEWED = 'interviewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    // Relationships
    public function unemployed()
    {
        return $this->belongsTo(Unemployed::class);
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    // Attribute Accessors
    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_REVIEWING => 'En revisión',
            self::STATUS_INTERVIEWED => 'Entrevistado',
            self::STATUS_ACCEPTED => 'Aceptado',
            self::STATUS_REJECTED => 'Rechazado',
            self::STATUS_WITHDRAWN => 'Retirado'
        ][$this->status] ?? 'Desconocido';
    }

    public function getResumeUrlAttribute()
    {
        return $this->resume_path ? asset('storage/' . $this->resume_path) : null;
    }

    // Helper Methods
    public function isActive()
    {
        return !in_array($this->status, [self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    public function canBeWithdrawn()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_REVIEWING]);
    }

    public function withdraw()
    {
        if ($this->canBeWithdrawn()) {
            $this->status = self::STATUS_WITHDRAWN;
            $this->save();
            return true;
        }
        return false;
    }

    public function updateStatus($status)
    {
        if (in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_REVIEWING,
            self::STATUS_INTERVIEWED,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_WITHDRAWN
        ])) {
            $this->status = $status;
            $this->save();
            return true;
        }
        return false;
    }
}
