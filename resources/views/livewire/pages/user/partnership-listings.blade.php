<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Partnerships') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200 flex items-center justify-between">
                <h1 class="text-2xl font-medium text-gray-900">
                    Partnerships
                </h1>

                <div class="mt-1 text-gray-500 leading-relaxed">
                    @if (! $partnerships)
                        {{ $this->createAction }}
                    @endif
                </div>
            </div>

            <div class="bg-white py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
                @if ($partnerships->count() === 1)
                    <x-alert.info title="Waiting for approval" class="mb-6">
                        {{ __('Your partnership is under review. Once this is approved, you\'ll be able to see other users\' partnerships.') }}
                    </x-alert.info>
                @endif

                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @forelse ($partnerships as $partnership)

                        <article class="flex flex-col items-start justify-between">
                            <div class="relative w-full">
                                <img src="{{ $partnership->image }}" alt="" class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                                    <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
                                </div>
                                <div class="max-w-xl">
                                <div class="mt-8 flex items-center gap-x-4 text-xs">
                                    <time datetime="{{ $partnership->created_at->format('Y-m-d') }}" class="text-gray-500">
                                        {{ $partnership->created_at->diffForHumans() }}
                                    </time>
                                    <!-- <a href="#" class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">Badge</a> -->
                                </div>
                                <div class="group relative">
                                    <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                        <a href="{{ $partnership->link }}" target="_blank">
                                            <span class="absolute inset-0"></span>
                                            {{ $partnership->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">
                                        {{ $partnership->description }}
                                    </p>
                                </div>
                                <div class="relative mt-8 flex items-center gap-x-4">
                                    <img src="{{ $partnership->user->avatar }}" alt="{{ $partnership->user->discord_username }}" class="h-10 w-10 rounded-full bg-gray-100">
                                    <div class="text-sm leading-6">
                                        <p class="font-semibold text-gray-900">
                                            <a href="#">
                                                <span class="absolute inset-0"></span>
                                                {{ $partnership->user->name }}
                                            </a>
                                        </p>
                                        <p class="text-gray-600">
                                            {{ $partnership->user->discord_username }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-3">
                            <x-empty-state
                                title="Partnership"
                                message="You need to have your partnership approved.">
                                <livewire:user.partnership-create />
                            </x-empty-state>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 px-4">
                {{ $partnerships->links() }}
            </div>
        </div>
    </div>
</div>
