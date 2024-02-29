<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserWorkExperience;
use Illuminate\Auth\Access\Response;

class UserWorkExperiencePolicy {
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,  User $model): bool {
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user,  User $model): bool {
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserWorkExperience $userWorkExperience): bool {
        return $user->id === $userWorkExperience->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $experienceId): bool {
        $userWorkExperience = UserWorkExperience::findOrFail($experienceId);

        return $user->id === $userWorkExperience->user_id;
    }
    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, UserWorkExperience $userWorkExperience): bool
    // {
    //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, UserWorkExperience $userWorkExperience): bool
    // {
    //
    // }
}
