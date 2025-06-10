<?php

namespace App\Listeners;

use App\Events\JobOfferStatusChanged;
use App\Models\Notification;

class NotifyFavoriteUsersAboutOfferStatus
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobOfferStatusChanged $event): void
    {
        $jobOffer = $event->jobOffer;
        $status = $event->newStatus ? 'activada' : 'desactivada';

        // Notify users who have favorited this job offer and have notifications enabled
        foreach ($jobOffer->favoriteUnemployed as $unemployed) {
            $favorite = $unemployed->favoriteOffers()->where('job_offer_id', $jobOffer->id)->first();
            
            // Only notify if user has status change notifications enabled
            if ($favorite && $favorite->hasNotificationEnabled('status_changes')) {
                Notification::create([
                    'user_id' => $unemployed->user_id,
                    'type' => 'offer_status_changed',
                    'notifiable_type' => get_class($jobOffer),
                    'notifiable_id' => $jobOffer->id,
                    'data' => [
                        'job_title' => $jobOffer->title,
                        'company_name' => $jobOffer->company->name,
                        'status' => $status
                    ]
                ]);
            }
        }
    }
}
