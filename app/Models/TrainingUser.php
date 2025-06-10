<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'user_id',
        'status',
        'progress',
        'notes',
        'completed_at',
        'certificate_id'
    ];

    protected $casts = [
        'progress' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Enrollment status constants
    const STATUS_ENROLLED = 'enrolled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DROPPED = 'dropped';

    // Relationships
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_DROPPED]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeByProgress($query, $minProgress)
    {
        return $query->where('progress', '>=', $minProgress);
    }

    // Helper Methods
    public function updateProgress(int $progress): void
    {
        $this->progress = min(100, max(0, $progress));
        
        if ($this->progress >= 100) {
            $this->complete();
        } elseif ($this->status === self::STATUS_ENROLLED) {
            $this->status = self::STATUS_IN_PROGRESS;
        }

        $this->save();
    }

    public function complete(): void
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            $this->status = self::STATUS_COMPLETED;
            $this->progress = 100;
            $this->completed_at = now();
            
            if ($this->training->certification_available && !$this->certificate_id) {
                $this->certificate_id = $this->generateCertificateId();
            }
            
            $this->save();
        }
    }

    public function drop(): void
    {
        $this->status = self::STATUS_DROPPED;
        $this->save();
    }

    public function isActive(): bool
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_DROPPED]);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getCertificateUrl(): ?string
    {
        if (!$this->certificate_id || !$this->isCompleted()) {
            return null;
        }

        return route('certificates.show', $this->certificate_id);
    }

    protected function generateCertificateId(): string
    {
        return strtoupper(uniqid($this->user_id . '-' . $this->training_id . '-'));
    }
}
