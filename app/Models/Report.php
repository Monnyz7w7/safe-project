<?php

namespace App\Models;

use App\Enum\Status;
use App\Traits\HasStatus;
use App\Traits\Approvable;
use Illuminate\Support\Str;
use App\Observers\ReportObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(ReportObserver::class)]
class Report extends Model
{
    use HasFactory,
        HasStatus;

    protected $attributes = [
        'status' => Status::UNDER_REVIEW
    ];

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }

    public function discordServer(): BelongsTo
    {
        return $this->belongsTo(DiscordServer::class);
    }

    public function scopeTopReportedUsers(Builder $query, int $limit = 8): Builder
    {
        return $query->select(
                DB::raw('MAX(id) as id'),
                'discord_user_id',
                DB::raw('MAX(discord_user_avatar_url) as discord_user_avatar_url'),
                'discord_user_global_name',
                'discord_username',
                DB::raw('COUNT(*) as total_report')
            )
            ->groupBy('discord_user_id', 'discord_user_global_name', 'discord_username')
            ->orderBy('total_report', 'desc')
            ->limit($limit);
    }

    public function defaultDiscordAvatar()
    {
        return 'https://cdn.discordapp.com/embed/avatars/0.png';
    }

    public function markAsVerified()
    {
        $this->update([
            'status' => Status::APPROVED,
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
    }

    public function isOwned(): bool
    {
        return $this->user_id === auth()->id();
    }

    protected function casts(): array
    {
        return $casts = [
            'action_at' => 'datetime',
            'status' => Status::class
        ];
    }

    protected function HtmlableDetails(): Attribute
    {
        return Attribute::make(
            get: fn () => new HtmlString($this->details)
        );
    }

    protected function discordUserAvatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::isUrl($value) ? $value : asset('storage/' . $value)
        );
    }
}
