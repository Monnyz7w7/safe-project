<div class="flex items-center gap-x-2">
    <div>
        <img src="{{ str($getRecord()->discord_user_avatar_url)->isUrl() ? $getRecord()->discord_user_avatar_url ?? $getRecord()->defaultDiscordAvatar() : asset('storage/' . $getRecord()->discord_user_avatar_url) }}" alt="{{ $getRecord()->discord_username }}" class="w-10 h-10 rounded-full">
    </div>
    <div>
        <span class="text-sm block">
            {{ $getState() }}
        </span>
        <span class="text-xs block text-gray-500 dark:text-gray-400">
            {{ $getRecord()->discord_user_id }}
        </span>
    </div>
</div>
