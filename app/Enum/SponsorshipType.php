<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SponsorshipType: string implements HasLabel, HasColor, HasIcon
{
    case PROJECT = 'project';

    case EVENT = 'event';

    public function getLabel(): ?string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match($this) {
            self::PROJECT => 'info',
            self::EVENT => 'danger',
            default => 'primary'
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::PROJECT => 'heroicon-m-rocket-launch',
            self::EVENT => 'heroicon-m-calendar-days'
        };
    }
}
