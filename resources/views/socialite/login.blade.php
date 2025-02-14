<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="space-y-2">
            <a href="{{ route('auth.redirect', 'discord') }}" class="border border-gray-200 bg-gray-800 text-white flex items-center justify-center h-12 font-semibold rounded-lg">
                Sign in with Discord
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>
