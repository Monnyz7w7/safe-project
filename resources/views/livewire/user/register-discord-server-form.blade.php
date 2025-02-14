<div>
    <form wire:submit.prevent="{{ $this->formAction() }}" action="#" method="POST" class="space-y-6">
        <div>
            <x-label for="serverName" value="{{ __('Discord Server Name') }}" />
            <x-input wire:model="serverName" id="serverName" type="text" class="mt-1 block w-full" />
            <x-input-error for="serverName" class="mt-2" />
        </div>

        <div>
            <x-label for="serverId" value="{{ __('Discord Server ID') }}" />
            <x-input wire:model="serverId" id="serverId" type="text" class="mt-1 block w-full" />
            <x-input-error for="serverId" class="mt-2" />
        </div>

        <div>
            <x-label for="serverInvitationLink" value="{{ __('Invitation Link') }}" />
            <x-input wire:model="serverInvitationLink" id="serverInvitationLink" type="text" class="mt-1 block w-full" />
            <x-input-error for="serverInvitationLink" class="mt-2" />
        </div>

        <div class="text-right">
            <x-button>
                {{ __($this->formAction()) }}
            </x-button>
        </div>
    </form>
</div>
