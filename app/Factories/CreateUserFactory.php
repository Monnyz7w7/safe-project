<?php

namespace App\Factories;

use App\Actions\Socialite\CreateDiscordUser;

class CreateUserFactory
{
    public function forService(string $service)
    {
        return match ($service) {
            'discord' => new CreateDiscordUser(),
            default => throw new \Exception('Invalid Service')
        };
    }
}
