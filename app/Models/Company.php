<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JobOffer;

class Company extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function JobOffers()
    {
        return $this->hasMany(JobOffer::class);
    }
}
