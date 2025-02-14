@props([
    'steps' => [
        ['title' => 'Search by User ID', 'body' => 'When searching for a user you will have to search for them using their User ID, how can you find them? Read this short guide (Where can I find my User/Server/Message ID? - Discord) or watch this short <a href="https://www.youtube.com/watch?v=mc3cV57m3mM" target="_blank" class="text-indigo-500 hover:underline">video from YouTube</a>.'],
        ['title' => 'Different Reports', 'body' => 'There are different types of reports, click on the badge next to the report to find out where the report came from.'],
        ['title' => 'Warning', 'body' => 'Do not pass information you find in here to the outside world, for security reasons. Remember that reports are made by other users and checked by our staff.']
    ]
])

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-steps :steps="$steps" />

            <div class="max-w-lg mx-auto my-12 px-4">
                <form wire:submit="search" method="POST">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 sr-only">Search report</label>
                    <div class="mt-2 flex rounded-md shadow-sm">
                        <div class="relative flex flex-grow items-stretch focus-within:z-10">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z" />
                                </svg>
                            </div>
                            <input wire:model="userId" type="text" name="search" id="search" class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Enter Discord ID">
                        </div>
                        <button type="submit" class="relative -ml-px inline-flex items-center gap-x-1.5 px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 -ml-0.5 h-5 w-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>

                            Search
                        </button>

                        <button wire:click="resetResult" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 -ml-0.5 h-5 w-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>

                            Reset
                        </button>
                    </div>
                </form>
            </div>

            <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-4">
                @forelse ($reports as $report)
                    <li class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg shadow {{ $report->isOwned() ? 'bg-purple-50' : 'bg-white' }}">
                        <div class="flex flex-1 flex-col p-4">
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
                                    <div class="mt-6 space-y-3 text-lg text-gray-500 text-center bg-gray-400/5 p-4">
                                        <h3 class="text-sm font-semibold leading-6 text-gray-600">
                                            Total Reports
                                        </h3>
                                        <p class="order-first text-3xl font-semibold tracking-tight text-gray-900">
                                            {{ $report->total_report }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="flex w-0 flex-1">
                                    <a href="{{ route('reports.search.user', $report->discord_user_id) }}" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-gray-700 hover:bg-gray-50/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                            <path fill-rule="evenodd" d="M17.303 5.197A7.5 7.5 0 0 0 6.697 15.803a.75.75 0 0 1-1.061 1.061A9 9 0 1 1 21 10.5a.75.75 0 0 1-1.5 0c0-1.92-.732-3.839-2.197-5.303Zm-2.121 2.121a4.5 4.5 0 0 0-6.364 6.364.75.75 0 1 1-1.06 1.06A6 6 0 1 1 18 10.5a.75.75 0 0 1-1.5 0c0-1.153-.44-2.303-1.318-3.182Zm-3.634 1.314a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 0 1-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 0 1-1.247-.606l.569-9.47a.75.75 0 0 1 .554-.68Z" clip-rule="evenodd" />
                                        </svg>
                                        View All Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <x-no-result class="max-w-sm col-span-full mx-auto" />
                @endforelse
            </ul>
        </div>
    </div>
</div>
