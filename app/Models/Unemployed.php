<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Portfolio;
use App\Models\FavoriteOffer;
use App\Models\TrainingUser;

class Unemployed extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function JobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function Portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function FavoriteOffers()
    {
        return $this->hasMany(FavoriteOffer::class);
    }

    public function TrainingUsers()
    {
        return $this->hasMany(TrainingUser::class);
    }
}
