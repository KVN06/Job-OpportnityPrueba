<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobOffer;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'type' => 'string'
    ];

    public function jobOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'job_offer_categories');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getNameAttribute($value)
    {
        return trim($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
