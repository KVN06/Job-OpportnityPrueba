<?php

namespace App\Listeners;

use App\Events\TrainingEnrollmentRequested;
use App\Models\Notification;
use App\Models\TrainingUser;

class ProcessTrainingEnrollment
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TrainingEnrollmentRequested $event): void
    {
        $training = $event->training;
        $user = $event->user;

        // Create the enrollment
        $enrollment = TrainingUser::create([
            'user_id' => $user->id,
            'training_id' => $training->id,
            'status' => 'enrolled',
            'progress' => 0
        ]);

        // Notify the company that offers the training
        Notification::create([
            'user_id' => $training->company->user_id,
            'type' => 'new_training_enrollment',
            'notifiable_type' => get_class($enrollment),
            'notifiable_id' => $enrollment->id,
            'data' => [
                'training_title' => $training->title,
                'participant_name' => $user->name,
                'enrollment_id' => $enrollment->id
            ]
        ]);

        // Notify the enrolled user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'training_enrollment_confirmed',
            'notifiable_type' => get_class($enrollment),
            'notifiable_id' => $enrollment->id,
            'data' => [
                'training_title' => $training->title,
                'company_name' => $training->company->name,
                'start_date' => $training->start_date->format('Y-m-d'),
                'enrollment_id' => $enrollment->id
            ]
        ]);
    }
}
