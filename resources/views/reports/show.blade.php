<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h1 class="text-base font-semibold leading-7 text-gray-900">
                            Report Information
                        </h1>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                            Discord user's details report
                        </p>
                    </div>

                    <div>
                        @if ($report->isVerified())
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-orange-50 px-2 py-1 text-xs font-medium text-orange-700 ring-1 ring-inset ring-orange-600/20">
                                Under Review
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-200 bg-opacity-25 p-6 lg:p-8">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Server
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discord_server }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                User ID
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discord_user_id }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Username
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discord_username }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Global Name
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discord_user_global_name }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Avatar
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                @if (! empty($report->discord_user_avatar_url))
                                    <img class="inline-block h-14 w-14 rounded-full" src="{{ $report->discord_user_avatar_url }}" alt="Avatar">
                                @else
                                    <div class="bg-gray-200 w-14 h-14 rounded-full flex items-center justify-center">
                                        <svg viewBox="0 0 640 512" fill="currentColor" class="text-indigo-500 w-8 h-8"><path d="M524.5 69.8a1.5 1.5 0 00-.8-.7A485.1 485.1 0 00404.1 32a1.8 1.8 0 00-1.9.9 337.5 337.5 0 00-14.9 30.6 447.8 447.8 0 00-134.4 0 309.5 309.5 0 00-15.1-30.6 1.9 1.9 0 00-1.9-.9 483.7 483.7 0 00-119.8 37.1 1.7 1.7 0 00-.8.7C39.1 183.7 18.2 294.7 28.4 404.4a2 2 0 00.8 1.4A487.7 487.7 0 00176 479.9a1.9 1.9 0 002.1-.7 348.2 348.2 0 0030-48.8 1.9 1.9 0 00-1-2.6 321.2 321.2 0 01-45.9-21.9 1.9 1.9 0 01-.2-3.1c3.1-2.3 6.2-4.7 9.1-7.1a1.8 1.8 0 011.9-.3c96.2 43.9 200.4 43.9 295.5 0a1.8 1.8 0 011.9.2c2.9 2.4 6 4.9 9.1 7.2a1.9 1.9 0 01-.2 3.1 301.4 301.4 0 01-45.9 21.8 1.9 1.9 0 00-1 2.6 391.1 391.1 0 0030 48.8 1.9 1.9 0 002.1.7 486 486 0 00147.2-74.1 1.9 1.9 0 00.8-1.4c12.2-126.7-20.6-236.8-87-334.5zm-302 267.8c-29 0-52.8-26.6-52.8-59.2s23.4-59.3 52.8-59.3c29.7 0 53.3 26.8 52.8 59.2 0 32.7-23.4 59.3-52.8 59.3zm195.4 0c-29 0-52.8-26.6-52.8-59.2s23.3-59.3 52.8-59.3c29.7 0 53.3 26.8 52.8 59.2 0 32.7-23.2 59.3-52.8 59.3z"/></svg>
                                    </div>
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Report Details
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->details }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
