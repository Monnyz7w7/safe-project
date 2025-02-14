<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report Details') }}
        </h2>
    </x-slot>

    <x-slot name="rightHeader">
        <x-nav-link href="{{ route('reports.search.user', $report->discord_user_id) }}" class="gap-x-1.5 text-gray-700 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M14 8a.75.75 0 0 1-.75.75H4.56l1.22 1.22a.75.75 0 1 1-1.06 1.06l-2.5-2.5a.75.75 0 0 1 0-1.06l2.5-2.5a.75.75 0 0 1 1.06 1.06L4.56 7.25h8.69A.75.75 0 0 1 14 8Z" clip-rule="evenodd" />
            </svg>

            Back to search reports
        </x-nav-link>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <div class="px-4 sm:px-0 flex items-center space-x-6">
                    <div>
                        <img
                            src="{{ $report->discord_user_avatar_url }}"
                            alt="{{ $report->discord_username }}"
                            class="rounded-full size-16"
                        >
                    </div>
                    <div>
                        <h3 class="text-base font-semibold leading-7 text-gray-900">
                            {{ $report->discord_username }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                            {{ $report->discord_user_id }}
                        </p>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-100">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Status
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                <x-filament::badge size="sm" :color="$report->status->getColor()" class="px-2 inline-flex">
                                    {{ $report->status->getLabel() }}
                                </x-filament::badge>
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Reported by
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->user->name }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Global Name
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discord_user_global_name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Server
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $report->discordServer->name }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Details
                            </dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 prose">
                                {{ $report->htmlable_details }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
