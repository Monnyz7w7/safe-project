<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasLabel, HasColor
{
    case ADMIN = 'admin';

    case USER = 'user';

    case PROMOTER = 'promoter';

    case DEVELOPER = 'developer';

    case INSIDER = 'insider';

    case VALIDATOR = 'validator';

    case WATCHER = 'watcher';

    public function getLabel(): ?string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match($this) {
            self::ADMIN => 'danger',
            self::USER => 'info',
            self::PROMOTER => 'success',
            self::DEVELOPER => 'warning',
            self::VALIDATOR => 'primary',
            self::INSIDER => 'tertiary',
            self::WATCHER => 'quaternary',
            default => 'gray'
        };
    }
}
