<?php

namespace App\Traits;

use App\Enum\Status;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

trait Approvable
{
    public function isApproved()
    {
        return (bool) $this->status === Status::APPROVED;
    }

    public function markAction($status)
    {
        $this->update([
            'status' => $status,
            'action_at' => now(),
            'action_by' => auth()->id()
        ]);
    }

    public function approve()
    {
        $this->update([
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
    }

    public static function actionApprovable()
    {
        return Action::make('Approve')
            ->color('success')
            ->requiresConfirmation()
            ->icon('heroicon-m-check-circle')
            ->visible(fn (Model $record) => ! $record->isApproved())
            ->modalHeading(fn (Model $record) => 'Approve ' . class_basename($record))
            ->action(fn (Model $record) => $record->approve())
            ->after(fn (Model $record) => Notification::make()->success()->title('Success')
                ->body(fn () => class_basename($record) . ' has been approved.')
                ->send()
        );
    }
}
