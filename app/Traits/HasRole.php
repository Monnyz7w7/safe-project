<?php

namespace App\Traits;

use App\Enum\Role;

trait HasRole
{
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isDeveloper(): bool
    {
        return $this->role === Role::DEVELOPER;
    }

    public function isPromoter(): bool
    {
        return $this->role === Role::PROMOTER;
    }

    public function isValidator(): bool
    {
        return $this->role === Role::VALIDATOR;
    }

    public function isInsider(): bool
    {
        return $this->role === Role::INSIDER;
    }

    public function isWatcher(): bool
    {
        return $this->role === Role::WATCHER;
    }

    public function isUser(): bool
    {
        return $this->role === Role::USER;
    }
}
