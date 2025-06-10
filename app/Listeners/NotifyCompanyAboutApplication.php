<?php

namespace App\Listeners;

use App\Events\JobApplicationSubmitted;
use App\Models\Notification;

class NotifyCompanyAboutApplication
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
        $company = $jobOffer->company;
        
        Notification::create([
            'user_id' => $company->user_id,
            'type' => 'new_application',
            'notifiable_type' => get_class($application),
            'notifiable_id' => $application->id,
            'data' => [
                'job_title' => $jobOffer->title,
                'applicant_name' => $application->unemployed->user->name,
                'application_id' => $application->id
            ]
        ]);
    }
}
