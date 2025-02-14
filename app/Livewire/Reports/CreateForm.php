<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Livewire\Component;
use App\Services\StoreImage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Http;
use Facades\App\Services\DiscordLookUp;
use Illuminate\Validation\ValidationException;

class CreateForm extends Component
{
    public $discordUserId = null;

    public $discordServer = null;

    public $details = null;

    #[Locked]
    public $discordUserData = null;

    public function render()
    {
        return view('livewire.reports.create-form');
    }

    public function fetchDiscordUserData()
    {
        $this->validate([
            'discordUserId' => ['required', 'integer']
        ]);

        $this->discordUserData = null;

        try {
            $this->discordUserData = DiscordLookUp::find($this->discordUserId);
        } catch (\Throwable $e) {
            // Handle the error
        }
    }

    public function report()
    {
        $this->validate([
            'discordServer' => ['string', 'min:4', 'max:255'],
            'details' => ['string', 'min:10', 'max:80000']
        ]);

        Report::create([
            'discord_server' => $this->discordServer,
            'discord_username' => $this->discordUserUsername(),
            'discord_user_id' => $this->discordUserId,
            'discord_user_avatar_url' => StoreImage::save($this->discordUserAvatar()),
            'discord_user_global_name' => $this->discordUserGlobalName(),
            'details' => $this->details,
            'user_id' => auth()->id()
        ]);

        $this->reset();

        return redirect()->route('reports.index')
            ->with('banner', 'Post successfully created.');
    }

    #[Computed]
    public function discordUserUsername()
    {
        return $this->discordUserData['username'] ?? '';
    }

    #[Computed]
    public function discordUserGlobalName()
    {
        return $this->discordUserData['global_name'] ?? '';
    }

    #[Computed]
    public function discordUserAvatar()
    {
        return $this->discordUserData['avatar_url'] ?? '';
    }

    protected function isDiscordUserFound()
    {
        // Probably better to check valid keys
        return count($this->discordUserData) <= 2 ? false : true;
    }
}
