<?php

namespace App\Filament\Resources\DiscordServerResource\Pages;

use App\Filament\Resources\DiscordServerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscordServer extends EditRecord
{
    protected static string $resource = DiscordServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
