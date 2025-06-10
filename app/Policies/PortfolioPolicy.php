<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view portfolios
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Portfolio $portfolio): bool
    {
        return true; // Anyone can view individual portfolios
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->type === 'unemployed';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'admin' || 
               ($user->type === 'unemployed' && $portfolio->unemployed_id === $user->unemployed->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'admin' || 
               ($user->type === 'unemployed' && $portfolio->unemployed_id === $user->unemployed->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'unemployed' && $portfolio->unemployed_id === $user->unemployed->id;
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, Portfolio $portfolio): bool
    {
        return $user->type === 'admin' || 
               ($user->type === 'unemployed' && $portfolio->unemployed_id === $user->unemployed->id);
    }
}
