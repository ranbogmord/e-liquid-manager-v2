<?php

namespace App\Policies;

use App\User;
use App\Liquid;
use Illuminate\Auth\Access\HandlesAuthorization;

class LiquidPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the liquid.
     *
     * @param  \App\User  $user
     * @param  \App\Liquid  $liquid
     * @return mixed
     */
    public function view(User $user, Liquid $liquid)
    {
        return $user->id === $liquid->author_id;
    }

    /**
     * Determine whether the user can create liquids.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the liquid.
     *
     * @param  \App\User  $user
     * @param  \App\Liquid  $liquid
     * @return mixed
     */
    public function update(User $user, Liquid $liquid)
    {
        return $user->id === $liquid->author_id;
    }

    /**
     * Determine whether the user can delete the liquid.
     *
     * @param  \App\User  $user
     * @param  \App\Liquid  $liquid
     * @return mixed
     */
    public function delete(User $user, Liquid $liquid)
    {
        return $user->id === $liquid->author_id;
    }
}
