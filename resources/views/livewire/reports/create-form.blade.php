<div>
    <div class="border-b border-gray-100 pb-6">
        <form wire:submit="fetchDiscordUserData()" method="POST" class="space-y-2">
            <div>
                <x-label for="name" value="{{ __('Discord User ID') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="discordUserId" required />
                <x-input-error for="discordUserId" class="mt-2" />
            </div>
            <div>
                <x-secondary-button type="submit">
                    Fetch Discord User's Data
                </x-secondary-button>
            </div>
        </form>
    </div>

    <form wire:submit="report()" method="POST" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5 {{ ! is_null($discordUserData) ?: 'blur-sm' }}">
        <div class="col-span-full">
            @if (! empty($this->discordUserAvatar))
                <img class="inline-block h-14 w-14 rounded-full" src="{{ $this->discordUserAvatar }}" alt="">
            @else
                <div class="bg-gray-200 w-14 h-14 rounded-full flex items-center justify-center">
                    <svg viewBox="0 0 640 512" fill="currentColor" class="text-indigo-500 w-8 h-8"><path d="M524.5 69.8a1.5 1.5 0 00-.8-.7A485.1 485.1 0 00404.1 32a1.8 1.8 0 00-1.9.9 337.5 337.5 0 00-14.9 30.6 447.8 447.8 0 00-134.4 0 309.5 309.5 0 00-15.1-30.6 1.9 1.9 0 00-1.9-.9 483.7 483.7 0 00-119.8 37.1 1.7 1.7 0 00-.8.7C39.1 183.7 18.2 294.7 28.4 404.4a2 2 0 00.8 1.4A487.7 487.7 0 00176 479.9a1.9 1.9 0 002.1-.7 348.2 348.2 0 0030-48.8 1.9 1.9 0 00-1-2.6 321.2 321.2 0 01-45.9-21.9 1.9 1.9 0 01-.2-3.1c3.1-2.3 6.2-4.7 9.1-7.1a1.8 1.8 0 011.9-.3c96.2 43.9 200.4 43.9 295.5 0a1.8 1.8 0 011.9.2c2.9 2.4 6 4.9 9.1 7.2a1.9 1.9 0 01-.2 3.1 301.4 301.4 0 01-45.9 21.8 1.9 1.9 0 00-1 2.6 391.1 391.1 0 0030 48.8 1.9 1.9 0 002.1.7 486 486 0 00147.2-74.1 1.9 1.9 0 00.8-1.4c12.2-126.7-20.6-236.8-87-334.5zm-302 267.8c-29 0-52.8-26.6-52.8-59.2s23.4-59.3 52.8-59.3c29.7 0 53.3 26.8 52.8 59.2 0 32.7-23.4 59.3-52.8 59.3zm195.4 0c-29 0-52.8-26.6-52.8-59.2s23.3-59.3 52.8-59.3c29.7 0 53.3 26.8 52.8 59.2 0 32.7-23.2 59.3-52.8 59.3z"/></svg>
                </div>
            @endif
        </div>

        <div>
            <x-label for="discordUserUsername" value="{{ __('Discord Username') }}" />
            <x-input id="discordUserUsername" type="text" class="mt-1 block w-full disabled:bg-gray-50" value="{{ $this->discordUserUsername }}" disabled />
        </div>

        <div>
            <x-label for="name" value="{{ __('Discord User Global Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full disabled:bg-gray-50" value="{{ $this->discordUserGlobalName }}" disabled />
        </div>

        <div>
            <x-label for="name" value="{{ __('Discord Server') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="discordServer" required />
            <x-input-error for="discordServer" class="mt-2" />
        </div>

        <div class="col-span-full">
            <x-label for="name" value="{{ __('Details of your report') }}" />
            <x-input-textarea id="name" rows="5" class="mt-1 block w-full" wire:model="details" required />
            <x-input-error for="details" class="mt-2" />
        </div>

        <div class="text-right col-span-full">
            <x-button type="submit">
                Submit &amp; Report User
            </x-button>
        </div>
    </form>
</div>
