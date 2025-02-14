<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    Report a user based on his/her Discord user ID
                </div>

                <div class="bg-gray-200 bg-opacity-25 p-6 lg:p-8">
                    <livewire:reports.create-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
