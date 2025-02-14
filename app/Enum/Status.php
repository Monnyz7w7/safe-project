<?php

namespace App\Enum;

use Illuminate\Support\Str;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel, HasColor, HasIcon
{
    case UNDER_REVIEW = 'Under Review';

    case APPROVED = 'Approved';

    case DECLINED = 'Declined';

    public function getLabel(): ?string
    {
        return Str::of($this->value)->title();
    }

    public function getColor(): string
    {
        return match($this) {
            self::UNDER_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::DECLINED => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::UNDER_REVIEW => 'heroicon-m-exclamation-circle',
            self::APPROVED => 'heroicon-m-check-circle',
            self::DECLINED => 'heroicon-m-x-circle',
        };
    }
}
