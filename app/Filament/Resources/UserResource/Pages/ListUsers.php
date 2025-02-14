<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\Status;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth('lg')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['status'] = Status::APPROVED;

                    return $data;
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserResource\Widgets\UserStatsWidget::class
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(label: 'All Users'),
            'approved' => Tab::make(label: 'Approved')
                ->modifyQueryUsing(callback: fn ($query) => $query->whereStatus(Status::APPROVED))
                ->badge(User::query()->whereStatus(Status::APPROVED)->count())
                ->badgeColor('success'),
            'pending' => Tab::make(label: 'Pending Approval')
                ->modifyQueryUsing(callback: fn ($query) => $query->whereStatus(Status::UNDER_REVIEW))
                ->badge(User::query()->whereStatus(Status::UNDER_REVIEW)->count())
                ->badgeColor('warning')
        ];
    }
}
