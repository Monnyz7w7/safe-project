<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Auth\Access\Response;

class SponsorshipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sponsorship $sponsorship): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

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
    public function update(User $user, Sponsorship $sponsorship): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles) || $sponsorship->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sponsorship $sponsorship): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles) || $sponsorship->user_id === $user->id;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Sponsorship $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can decline the model.
     */
    public function decline(User $user, Sponsorship $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

        return in_array($user->role, $allowedRoles);
    }
}
