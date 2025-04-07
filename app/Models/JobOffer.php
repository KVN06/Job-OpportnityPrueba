<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\Comment;
use App\Models\FavoriteOffer;
use App\Models\Category;
use App\Models\JobOfferCategory;

class JobOffer extends Model
{
    public function Company()
    {
        return $this->belongsTo(Company::class);
    }

    public function JobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function Comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function FavoriteOffers()
    {
        return $this->hasMany(FavoriteOffer::class);
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class, 'job_offer_category');
    }
}
