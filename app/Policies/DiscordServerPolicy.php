<?php

namespace App\Policies;

use App\Models\DiscordServer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiscordServerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DiscordServer $discordServer): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DiscordServer $discordServer): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DiscordServer $discordServer): bool
    {
        return auth()->user()->isAdmin();
    }
}
