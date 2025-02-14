<?php

namespace App\Policies;

use App\Enum\Role;
use App\Enum\Status;
use App\Models\User;
use App\Models\Partnership;
use Illuminate\Auth\Access\Response;

class PartnershipPolicy
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
    public function view(User $user, Partnership $partnership): bool
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
    public function update(User $user, Partnership $partnership): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles) ||
            (($partnership->user_id === $user->id) && $partnership->status === Status::UNDER_REVIEW);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Partnership $partnership): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, ?Partnership $model = null): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can decline the model.
     */
    public function decline(User $user, Partnership $model): bool
    {
        $allowedRoles = [Role::ADMIN, Role::WATCHER];

        return in_array($user->role, $allowedRoles);
    }
}
