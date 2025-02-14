<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Actions;
use App\Models\Report;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ReportResource;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Report::filamentAction(action: 'approve', type: 'header'),
            Report::filamentAction(action: 'decline', type: 'header'),
            Actions\Action::make('Visit Server')
                ->icon('heroicon-m-cursor-arrow-rays')
                ->requiresConfirmation()
                ->action(fn (Report $record) => $this->js("window.open('{$record->discordServer->invitation_link}')"))
        ];
    }
}
