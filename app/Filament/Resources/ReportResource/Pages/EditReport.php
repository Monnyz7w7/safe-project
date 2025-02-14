<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ReportResource;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected static ?string $title = 'Review and Verify Report';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
