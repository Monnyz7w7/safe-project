<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex items-center flex-col">
                <x-authentication-card-logo />

                <div class="max-w-lg">
                    <x-alert.warning title="Attenion">
                        <p>
                            {{ __('Before we can proceed with your approval, please provide your Discord server details below. Once this is done, you\'ll receive an email notification once our staff have reviewed and verified your registration.') }}
                        </p>
                        <p class="mt-4">
                            {{ __('You\'ll receive another email notification once your registration has been approved. Thank you for your cooperation!') }}
                        </p>
                        <p class="mt-4">
                            {{ __('In the meantime, feel free to edit your profile. We also recommend setting up a password if you want to directly login using your email and password.') }}
                        </p>
                    </x-alert.warning>
                </div>
            </div>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600"></div>

        <livewire:user.register-discord-server-form />

        <div class="mt-4 flex items-center justify-between">
            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Edit Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
