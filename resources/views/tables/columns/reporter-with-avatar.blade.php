<div class="flex items-center gap-x-2">
    <div>
        <img src="{{ $getRecord()->user->avatar }}" alt="{{ $getRecord()->user->discord_username }}" class="w-10 h-10 rounded-full">
    </div>
    <div>
        <span class="text-sm block">
            {{ $getState() }}
        </span>
        <span class="text-xs block text-gray-500 dark:text-gray-400">
            {{ $getRecord()->user->discord_id }}
        </span>
    </div>
</div>
