<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy
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
    public function view(User $user, JobApplication $application): bool
    {
        // El aplicante puede ver su propia aplicación
        if ($user->type === 'unemployed' && $application->unemployed->user_id === $user->id) {
            return true;
        }

        // La empresa puede ver las aplicaciones a sus ofertas
        if ($user->type === 'company') {
            return $application->jobOffer->company_id === $user->company->id;
        }

        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->type === 'unemployed' && $user->unemployed()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobApplication $application): bool
    {
        // Solo la empresa que publicó la oferta puede actualizar el estado
        if ($user->type === 'company') {
            return $application->jobOffer->company_id === $user->company->id;
        }

        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobApplication $application): bool
    {
        // El aplicante puede retirar su propia aplicación si está pendiente
        if ($user->type === 'unemployed' && $application->unemployed->user_id === $user->id) {
            return $application->status === 'pending';
        }

        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobApplication $application): bool
    {
        return $user->type === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobApplication $application): bool
    {
        return $user->type === 'admin';
    }
}
