<?php

namespace App\Listeners;

use App\Events\JobApplicationSubmitted;
use App\Models\Notification;

class SendApplicationConfirmationToUnemployed
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobApplicationSubmitted $event): void
    {
        $application = $event->jobApplication;
        $jobOffer = $application->jobOffer;
        $unemployed = $application->unemployed;
        
        Notification::create([
            'user_id' => $unemployed->user_id,
            'type' => 'application_submitted',
            'notifiable_type' => get_class($application),
            'notifiable_id' => $application->id,
            'data' => [
                'job_title' => $jobOffer->title,
                'company_name' => $jobOffer->company->name,
                'application_id' => $application->id
            ]
        ]);
    }
}
