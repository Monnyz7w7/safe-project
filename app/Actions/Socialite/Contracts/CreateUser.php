<?php

namespace App\Actions\Socialite\Contracts;

use App\Models\User;

interface CreateUser
{
    public function create($user): User;
}
