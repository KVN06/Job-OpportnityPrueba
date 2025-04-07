<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Training;
use App\Models\Unemployed;

class TrainingUser extends Model
{
    public function Training()
    {
        return $this->belongsTo(Training::class);
    }

    public function Unemployed()
    {
        return $this->belongsTo(Unemployed::class);
    }
}
