<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="flex items-center gap-x-5 trun">
        <div>
            <img src="{{ $getRecord()->user->avatar }}" alt="{{ $getRecord()->discord_username }}" class="w-20 h-20 rounded-full">
        </div>

        <div>
            <span class="text-base block font-semibold">
                {{ $getRecord()->user->name }}
            </span>
            <span class="text-sm block">
                {{ $getState() }}
            </span>
            <span class="text-xs block text-gray-500 dark:text-gray-400">
                {{ $getRecord()->user->discord_id }}
            </span>
        </div>
    </div>
</x-dynamic-component>
