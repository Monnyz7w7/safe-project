<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            User::filamentAction(action: 'approve', type: 'header'),

            Impersonate::make()->record($this->getRecord())
                ->color('gray'),

            Actions\EditAction::make()
                ->color('success')
                ->icon('heroicon-o-pencil-square'),

            Actions\Action::make('Visit Server')
                ->icon('heroicon-m-cursor-arrow-rays')
                ->requiresConfirmation()
                ->action(fn (User $record) => $this->js("window.open('{$record->discordServer->invitation_link}')"))
        ];
    }
}
