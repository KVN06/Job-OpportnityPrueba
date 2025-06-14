<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],
        Logout::class => [
            \App\Listeners\LogSuccessfulLogout::class,
        ],
        \App\Events\JobApplicationSubmitted::class => [
            \App\Listeners\NotifyCompanyAboutApplication::class,
            \App\Listeners\SendApplicationConfirmationToUnemployed::class,
        ],
        \App\Events\JobOfferStatusChanged::class => [
            \App\Listeners\NotifyFavoriteUsersAboutOfferStatus::class,
        ],
        \App\Events\TrainingEnrollmentRequested::class => [
            \App\Listeners\ProcessTrainingEnrollment::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register custom event subscribers
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
