<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Training;
use App\Models\Unemployed;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\Portfolio;
use App\Models\Message;
use App\Models\Comment;
use App\Models\FavoriteOffer;
use App\Policies\CompanyPolicy;
use App\Policies\TrainingPolicy;
use App\Policies\UnemployedPolicy;
use App\Policies\JobApplicationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        Unemployed::class => UnemployedPolicy::class,
        Training::class => TrainingPolicy::class,
        JobApplication::class => JobApplicationPolicy::class,
        Portfolio::class => PortfolioPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // User type gates
        Gate::define('admin', fn($user) => $user->type === 'admin');
        Gate::define('company', fn($user) => $user->type === 'company');
        Gate::define('unemployed', fn($user) => $user->type === 'unemployed');

        // JobOffer gates
        Gate::define('create-job-offer', fn($user) => 
            $user->type === 'company' || $user->type === 'admin'
        );

        Gate::define('manage-job-offer', function ($user, JobOffer $jobOffer) {
            return $user->type === 'admin' || 
                   ($user->type === 'company' && $jobOffer->company_id === $user->company->id);
        });

        // Portfolio gates
        Gate::define('manage-portfolio', function ($user, Portfolio $portfolio) {
            return $user->type === 'admin' || 
                   ($user->type === 'unemployed' && $portfolio->unemployed_id === $user->unemployed->id);
        });

        // Comment gates
        Gate::define('delete-comment', function ($user, Comment $comment) {
            return $user->type === 'admin' || $comment->user_id === $user->id;
        });

        // Message gates
        Gate::define('view-message', function ($user, Message $message) {
            return $message->sender_id === $user->id || $message->receiver_id === $user->id;
        });

        Gate::define('delete-message', function ($user, Message $message) {
            return $message->sender_id === $user->id || $message->receiver_id === $user->id;
        });

        // Favorite gates
        Gate::define('manage-favorites', function ($user, FavoriteOffer $favorite) {
            return $user->type === 'unemployed' && $favorite->unemployed_id === $user->unemployed->id;
        });

        // Training gates
        Gate::define('manage-training', function ($user, Training $training) {
            return $user->type === 'admin' || 
                   ($user->type === 'company' && $training->company_id === $user->company->id);
        });

        Gate::define('enroll-training', function ($user, Training $training) {
            return $user->type === 'unemployed' && 
                   !$training->participants()->where('unemployed_id', $user->unemployed->id)->exists();
        });

        // Company profile gates
        Gate::define('edit-company-profile', function ($user, Company $company) {
            return $user->type === 'admin' || 
                   ($user->type === 'company' && $company->id === $user->company->id);
        });

        // Unemployed profile gates
        Gate::define('edit-unemployed-profile', function ($user, Unemployed $unemployed) {
            return $user->type === 'admin' || 
                   ($user->type === 'unemployed' && $unemployed->id === $user->unemployed->id);
        });
    }
}
