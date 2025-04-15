<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ConnectedObject;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConnectedObjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ConnectedObject $object)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array($user->level, ['avancé', 'expert']) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ConnectedObject $object)
    {
        return in_array($user->level, ['avancé', 'expert']) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ConnectedObject $object)
    {
        return in_array($user->level, ['avancé', 'expert']) || $user->role === 'admin';
    }
}
