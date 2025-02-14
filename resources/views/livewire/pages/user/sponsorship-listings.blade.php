<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sponsorships') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200 flex items-center justify-between">
                <h1 class="text-2xl font-medium text-gray-900">
                    Sponsorships

                    <span class="text-xs font-medium text-yellow-800 bg-yellow-50 px-1 py-0.5">
                        You only have {{ auth()->user()->remaining_sponsorship_creation }} sponsorships left to create.
                    </span>
                </h1>

                @if (auth()->user()->canCreateSponsorship())
                    <div>
                        {{ $this->createAction }}
                    </div>
                @endif
            </div>

            <div class="bg-white py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @foreach ($sponsorships as $sponsorship)

                        <livewire:components.sponsorship-listing-item
                            :sponsorship="$sponsorship"
                            :key="$sponsorship->id"
                        />

                    @endforeach
                </div>
            </div>

            <div class="mt-6 px-4">
                {{ $sponsorships->links() }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
