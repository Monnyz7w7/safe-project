<article>
    <div class="relative w-full">
        <a href="{{ $sponsorship->link }}" target="_blank" class="hover:opacity-75 transition-opacity">
            <img src="{{ $sponsorship->image }}" alt="{{ $sponsorship->title }}" class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
            </div>
        </a>
        <div class="max-w-xl">
            <div class="mt-8 flex items-center justify-between gap-x-4 text-xs">
                <time datetime="{{ $sponsorship->created_at->format('Y-m-d') }}" class="text-gray-500">
                    {{ $sponsorship->created_at->diffForHumans() }}
                </time>
                <x-filament::badge size="sm" :color="$sponsorship->type->getColor()" class="px-4 ml-1.5">
                    {{ $sponsorship->type->getLabel() }}
                </x-filament::badge>
            </div>
        <div class="group relative">
            <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                <a href="{{ $sponsorship->link }}" target="_blank">
                    <span class="absolute inset-0"></span>
                    {{ $sponsorship->title }}
                </a>
            </h3>
            <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">
                {{ $sponsorship->description }}
            </p>
        </div>
        <div class="relative mt-8 flex items-center gap-x-4">
            <img src="{{ $sponsorship->user->avatar }}" alt="{{ $sponsorship->user->discord_username }}" class="h-10 w-10 rounded-full bg-gray-100">
            <div class="text-sm leading-6">
                <p class="font-semibold text-gray-900">
                    {{ $sponsorship->user->name }}
                </p>
                <p class="text-gray-600">
                    {{ $sponsorship->user->discord_username }}
                </p>
            </div>
        </div>
    </div>

    @if ($sponsorship->user_id === auth()->id())
        <div class="mt-4 w-full space-x-2 flex justify-between">
            <div class="text-sm inline-flex items-center text-gray-500">
                Status:
                <x-filament::badge size="sm" :color="$sponsorship->status->getColor()" class="px-4 ml-1.5">
                    {{ $sponsorship->status }}
                </x-filament::badge>
            </div>
            <div>
                {{-- @can ('update', $sponsorship)
                    {{ $this->editAction }}
                @endcan --}}

                @can ('delete', $sponsorship)
                    {{ $this->deleteAction }}
                @endcan
            </div>
        </div>
    @endif

    <x-filament-actions::modals />
</article>
