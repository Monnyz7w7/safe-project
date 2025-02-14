<?php

namespace App\Actions\Socialite;

use App\Models\User;
use App\Services\StoreImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Actions\Socialite\Contracts\CreateUser;

class CreateDiscordUser implements CreateUser
{
    protected $avatarFileName = null;

    public function create($user): User
    {
        $this->storeAvatar($user);

        return User::firstOrCreate([
            'discord_id' => $user->getId()
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'discord_avatar_url' => $user->getAvatar(),
            'discord_username' => $user->getRaw()['username'],
            'profile_photo_path' => $this->avatarFileName
        ]);
    }

    protected function storeAvatar($user)
    {
        $this->avatarFileName = StoreImage::save($user->getAvatar());
    }
}
