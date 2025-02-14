@props([
    'steps' => [
        ['title' => 'Note', 'body' => 'Only report users who have harmed your community, if you know of other users or servers that may harm your community please let us know here! (<a href="https://forms.gle/y7D6oHnt3SLRQExt7" target="_blank" class="text-indigo-500 hover:underline">Google Forms</a>).'],
        ['title' => 'Report via User ID', 'body' => 'To report a user you need their User ID, how can you find it? Read this short guide (Where can I find my User/Server/Message ID? - Discord) or watch this short <a href="https://www.youtube.com/watch?v=mc3cV57m3mM" target="_blank" class="text-indigo-500 hover:underline">video in YouTube</a>.'],
        ['title' => 'Provide Details', 'body' => 'Try to provide as much detail as possible when reporting another user, describe what they did and try to include pictures as evidence. Every report is verified by our staff.']
    ]
])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-steps :steps="$steps" />

            <div class="mt-12 p-6 lg:p-8 bg-white border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-medium text-gray-900">
                        Reported Users
                    </h1>

                    <p class="mt-1 text-gray-500 leading-relaxed">
                        Discord users you've reported.
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-lg font-semibold">{{ $discordServer->name }}</p>
                    <p class="text-gray-500 font-medium">{{ $discordServer->server_id }}</p>
                </div>
            </div>

            <div class="bg-white py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
                <livewire:reports.user.listing />
            </div>

        </div>
    </div>
</x-app-layout>
