<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unemployed;

class Portfolio extends Model
{
    public function Unemployed()
    {
        return $this->belongsTo(Unemployed::class);
    }
}
