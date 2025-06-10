<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
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
    public function view(User $user, Training $training): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->type, [User::TYPE_COMPANY, User::TYPE_ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Training $training): bool
    {
        // El creador de la capacitación o un administrador pueden actualizarla
        return $training->company_id === optional($user->company)->id || $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Training $training): bool
    {
        // El creador de la capacitación o un administrador pueden eliminarla
        return $training->company_id === optional($user->company)->id || $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Training $training): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Training $training): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }

    /**
     * Determine whether the user can enroll in trainings.
     */
    public function enroll(User $user, Training $training): bool
    {
        // Solo usuarios desempleados pueden inscribirse y no deben estar ya inscritos
        return $user->type === User::TYPE_UNEMPLOYED &&
               !$training->users()->where('user_id', $user->id)->exists() &&
               $training->status === 'published';
    }

    /**
     * Determine whether the user can mark training as completed.
     */
    public function complete(User $user, Training $training): bool
    {
        // Solo usuarios inscritos pueden marcar como completada
        return $user->type === User::TYPE_UNEMPLOYED &&
               $training->users()->where('user_id', $user->id)
                       ->where('status', 'enrolled')
                       ->exists();
    }
}
