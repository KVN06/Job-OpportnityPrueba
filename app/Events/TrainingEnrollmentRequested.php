<?php

namespace App\Events;

use App\Models\User;
use App\Models\Training;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingEnrollmentRequested
{
    use Dispatchable, SerializesModels;

    public $training;
    public $user;

    public function __construct(Training $training, User $user)
    {
        $this->training = $training;
        $this->user = $user;
    }
}
