<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports for ' . $discordUserId) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-center my-12 px-4">
                <form wire:submit="search" method="POST">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 sr-only">Search report</label>
                    <div class="mt-2 flex">
                        <x-cgp.dropdown>
                            <x-slot name="trigger">
                                <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 px-3 py-2 text-sm font-semibold text-gray-900 rounded-l-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                    Filters
                                    <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div>
                                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900">
                                        Status
                                    </label>
                                    <x-input-select wire:model="status" id="status" class="w-full text-sm">
                                        <option value="">All</option>
                                        @foreach ($statuses as $item)
                                            <option value="{{ $item->value }}">
                                                {{ $item->getLabel() }}
                                            </option>
                                        @endforeach
                                    </x-input-select>
                                </div>

                                <div class="mt-4">
                                    <x-button x-on:click="open = false" type="submit">
                                        Apply
                                    </x-button>
                                </div>
                            </x-slot>
                        </x-cgp.dropdown>

                        <button wire:click="resetResult" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 -ml-0.5 h-5 w-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>

                            Reset
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-xs mb-4 text-gray-700 px-4">
                Showing reports for
                <span class="font-semibold">{{ $discordUserId }}</span>, total of {{ $recordsCount }} records found.
            </div>

            <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-4">
                @forelse ($reports as $report)
                    <li class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg shadow {{ $report->isOwned() ? 'bg-purple-50' : 'bg-white' }}">
                        <div class="flex flex-1 flex-col p-8">
                            <ul role="list" class="divide-y divide-gray-100">
                                <li class="py-4">
                                    <div class="flex items-center gap-x-3">
                                        <img
                                            src="{{ $report->discord_user_avatar_url }}"
                                            alt="{{ $report->discord_username }}"
                                            class="h-12 w-12 flex-none rounded-full bg-gray-800"
                                        >

                                        <span>
                                            <h3 class="flex-auto truncate text-sm font-semibold leading-6 text-gray-900">
                                                {{ $report->discord_username }}
                                            </h3>
                                            <p class="text-xs text-gray-600">
                                                {{ $report->discord_user_id }}
                                            </p>
                                        </span>
                                    </div>
                                    <div class="mt-6 space-y-3 text-sm text-gray-500">
                                        <p class="truncate">
                                            Server:

                                            <span class="text-gray-800 font-medium">
                                                {{ $report->discordServer->name }}
                                            </span>
                                        </p>

                                        <p>
                                            Reported:

                                            <span class="text-gray-800 font-medium">
                                                {{ $report->created_at->diffForHumans() }}
                                            </span>
                                        </p>

                                        <p>
                                            Reported by:

                                            <span class="text-gray-800 font-medium">
                                                {{ $report->user->discord_username }}
                                            </span>
                                        </p>

                                        <p>
                                            Status:

                                            <x-filament::badge size="sm" :color="$report->status->getColor()" class="px-2 ml-1 inline-flex">
                                                {{ $report->status->getLabel() }}
                                            </x-filament::badge>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="flex w-0 flex-1">
                                    <a href="{{ route('reports.show', $report) }}" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-gray-700 hover:bg-gray-50/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                            <path fill-rule="evenodd" d="M17.303 5.197A7.5 7.5 0 0 0 6.697 15.803a.75.75 0 0 1-1.061 1.061A9 9 0 1 1 21 10.5a.75.75 0 0 1-1.5 0c0-1.92-.732-3.839-2.197-5.303Zm-2.121 2.121a4.5 4.5 0 0 0-6.364 6.364.75.75 0 1 1-1.06 1.06A6 6 0 1 1 18 10.5a.75.75 0 0 1-1.5 0c0-1.153-.44-2.303-1.318-3.182Zm-3.634 1.314a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 0 1-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 0 1-1.247-.606l.569-9.47a.75.75 0 0 1 .554-.68Z" clip-rule="evenodd" />
                                        </svg>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <x-no-result class="max-w-sm col-span-full mx-auto" />
                @endforelse
            </ul>

            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
