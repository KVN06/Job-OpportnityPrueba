<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unemployed;
use App\Models\JobOffer;

class JobApplication extends Model
{
    public function Unemployed()
    {
        return $this->belongsTo(Unemployed::class);
    }

    public function JobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }
}
