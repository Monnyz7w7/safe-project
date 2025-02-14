<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $allowedRoles = [Role::ADMIN, Role::VALIDATOR];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::VALIDATOR];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, User $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::VALIDATOR];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can decline the model.
     */
    public function decline(User $user, User $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::VALIDATOR];

        return in_array($user->role, $allowedRoles);
    }
}
