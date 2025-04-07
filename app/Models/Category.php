<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobOffer;

class Category extends Model
{
    public function JobOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'job_offer_category');
    }
}
