<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DiscordServer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Filament\Notifications\Notification;

class RegisterDiscordServerForm extends Component
{
    public $serverName = null;

    public $serverId = null;

    public $serverInvitationLink = null;

    #[Locked]
    public ?DiscordServer $discordServer = null;

    public function mount()
    {
        $this->discordServer = auth()->user()->discordServer;

        $this->serverName ??= $this->discordServer?->name;
        $this->serverId ??= $this->discordServer?->server_id;
        $this->serverInvitationLink ??= $this->discordServer?->invitation_link;
    }

    public function formAction()
    {
        return $this->discordServer ? 'update' : 'create';
    }

    public function create()
    {
        $validated = $this->validate();

        auth()->user()->discordServer()->create($this->formData($validated));

        $this->showNotification('Server details have been created.');
    }

    public function update()
    {
        $validated = $this->validate();

        $this->discordServer->update($this->formData($validated));

        $this->showNotification('Details have been updated.');
    }

    public function render()
    {
        return view('livewire.user.register-discord-server-form');
    }

    protected function rules()
    {
        return [
            'serverId' => [
                'required',
                Rule::unique(table: 'discord_servers', column: 'user_id')->ignore(auth()->id())
            ],
            'serverInvitationLink' => ['required', 'url', 'max:255'],
            'serverName' => ['required', 'min:4', 'max:255']
        ];
    }

    protected function formData(array $data)
    {
        return [
            'server_id' => $data['serverId'],
            'name' => $data['serverName'],
            'invitation_link' => $data['serverInvitationLink'],
        ];
    }

    protected function showNotification(string $message)
    {
        Notification::make()->success()->title('Success')
            ->body($message)
            ->send();
    }
}
