<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\User;
use App\Models\Report;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $allowedRoles = [Role::ADMIN, Role::INSIDER, Role::USER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        $allowedRoles = [Role::ADMIN, Role::INSIDER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $allowedRoles = [Role::ADMIN, Role::INSIDER];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $report->user_id === $user->id || auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $report->user_id === $user->id || auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Report $model): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can decline the model.
     */
    public function decline(User $user, Report $model): bool
    {
        $allowedRoles = [Role::ADMIN];

        return in_array($user->role, $allowedRoles);
    }
}
