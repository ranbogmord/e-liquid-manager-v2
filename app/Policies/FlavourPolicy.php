<?php

namespace App\Policies;

use App\User;
use App\Flavour;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlavourPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the flavour.
     *
     * @param  \App\User  $user
     * @param  \App\Flavour  $flavour
     * @return mixed
     */
    public function view(User $user, Flavour $flavour)
    {
        return true;
    }

    /**
     * Determine whether the user can create flavours.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the flavour.
     *
     * @param  \App\User  $user
     * @param  \App\Flavour  $flavour
     * @return mixed
     */
    public function update(User $user, Flavour $flavour)
    {
        return $user->role === "admin";
    }

    /**
     * Determine whether the user can delete the flavour.
     *
     * @param  \App\User  $user
     * @param  \App\Flavour  $flavour
     * @return mixed
     */
    public function delete(User $user, Flavour $flavour)
    {
        return $user->role === "admin";
    }
}
