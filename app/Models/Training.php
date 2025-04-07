<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingUser;

class Training extends Model
{
    public function TrainingUsers()
    {
        return $this->hasMany(TrainingUser::class);
    }
}
    