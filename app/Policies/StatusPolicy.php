<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * tentukan penguna dapat melihat/akses model
     */
    public function view(User $user, Status $status): bool
    {
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user, Status $status): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can delete the model.
     */
    // public function delete(User $user, Status $status): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Status $status): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Status $status): bool
    // {
    //     //
    // }
}