<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\Role;
use Filament\Panel;
use App\Enum\Status;
use App\Traits\HasRole;
use App\Traits\HasStatus;
use App\Traits\Approvable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRole;
    use HasStatus;

    CONST MAX_SPONSORSHIP_PER_USER = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected $attributes = [
        'role' => Role::USER,
        'status' => Status::UNDER_REVIEW
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function discordServer(): HasOne
    {
        return $this->hasOne(DiscordServer::class);
    }

    public function partnership(): Hasone
    {
        return $this->hasOne(Partnership::class);
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! is_null($this->profile_photo_path)) {
                    return $this->profile_photo_url;
                }

                if (! is_null($this->discord_avatar_url)) {
                    return $this->discord_avatar_url;
                }

                return $this->profile_photo_url;
            }
        );
    }

    public function remainingSponsorshipCreation(): Attribute
    {
        return Attribute::make(
            get: fn () => self::MAX_SPONSORSHIP_PER_USER - $this->sponsorships()->count()
        );
    }

    public function canCreateSponsorship(): bool
    {
        return $this->sponsorships()->count() < self::MAX_SPONSORSHIP_PER_USER;
    }

    public function hasPassword(): bool
    {
        return ! is_null($this->password);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ! $this->isUser();
    }

    protected function casts()
    {
        return [
            'email_verified_at' => 'datetime',
            'role' => Role::class,
            'action_at' => 'datetime',
            'status' => Status::class
        ];
    }
}
