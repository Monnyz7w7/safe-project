<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="flex items-center gap-x-5">
        <div>
            <img src="{{ $getRecord()->discord_user_avatar_url ?? $getRecord()->defaultDiscordAvatar() }}" alt="{{ $getRecord()->discord_username }}" class="w-20 h-20 rounded-full">
        </div>

        <div>
            <span class="text-base block font-semibold">
                {{ $getRecord()->discord_user_global_name }}
            </span>
            <span class="text-sm block">
                {{ $getRecord()->discord_username }}
            </span>
            <span class="text-xs block text-gray-500 dark:text-gray-400">
                {{ $getState() }}
            </span>
        </div>
    </div>

</x-dynamic-component>
