<?php

namespace App\Policies;

use App\Models\Farm;
use App\Models\User;

/**
 * Authorization policy for Farm resources.
 * All actions are restricted to the farm's owner.
 */
class FarmPolicy
{
    /**
     * Determine if the user can view the farm.
     *
     * @param  User $user
     * @param  Farm $farm
     * @return bool
     */
    public function view(User $user, Farm $farm): bool
    {
        return $user->id === $farm->user_id;
    }

    /**
     * Determine if the user can create farms.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the farm.
     *
     * @param  User $user
     * @param  Farm $farm
     * @return bool
     */
    public function update(User $user, Farm $farm): bool
    {
        return $user->id === $farm->user_id;
    }

    /**
     * Determine if the user can delete the farm.
     *
     * @param  User $user
     * @param  Farm $farm
     * @return bool
     */
    public function delete(User $user, Farm $farm): bool
    {
        return $user->id === $farm->user_id;
    }
}
