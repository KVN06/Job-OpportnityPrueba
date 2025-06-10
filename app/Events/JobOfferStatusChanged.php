<?php

namespace App\Events;

use App\Models\JobOffer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobOfferStatusChanged
{
    use Dispatchable, SerializesModels;

    public $jobOffer;
    public $oldStatus;
    public $newStatus;

    public function __construct(JobOffer $jobOffer, bool $oldStatus, bool $newStatus)
    {
        $this->jobOffer = $jobOffer;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
