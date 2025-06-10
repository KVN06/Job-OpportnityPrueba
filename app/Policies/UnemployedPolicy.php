<?php

namespace App\Policies;

use App\Models\Unemployed;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnemployedPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Unemployed $unemployed): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->type === User::TYPE_UNEMPLOYED && !$user->unemployed()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unemployed $unemployed): bool
    {
        return $user->id === $unemployed->user_id || $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Unemployed $unemployed): bool
    {
        return $user->id === $unemployed->user_id || $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Unemployed $unemployed): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Unemployed $unemployed): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }
}
