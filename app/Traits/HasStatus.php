<?php

namespace App\Traits;

use App\Enum\Status;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Actions\Action as FilamentAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Tables\Actions\Action as FilamentTableAction;
use Illuminate\Contracts\Container\BindingResolutionException;

trait HasStatus
{
    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isUnderReview(): bool
    {
        return $this->status === Status::UNDER_REVIEW;
    }

    public function isDeclined(): bool
    {
        return $this->status === Status::DECLINED;
    }

    public function markAction($status)
    {
        $this->update([
            'status' => $status,
            'action_at' => now(),
            'action_by' => auth()->id()
        ]);
    }

    protected static function filamentAction($action, $type = 'table')
    {
        if (! in_array($action, ['approve', 'decline'])) {
            return;
        }

        if (! in_array($type, ['table', 'header'])) {
            return;
        }

        $filamentAction = $type === 'table' ? FilamentTableAction::class : FilamentAction::class;

        $statusAction = $action === 'approve' ? Status::APPROVED : Status::DECLINED;

        return $filamentAction::make(Str::title($action))
            ->color($action === 'approve' ? 'success' : 'danger')
            ->requiresConfirmation()
            ->icon($action === 'approve' ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
            ->visible(fn (Model $record) => $record->isUnderReview() && auth()->user()->can($action, $record))
            ->modalHeading(fn (Model $record) => Str::title($action) . ' ' . class_basename($record))
            ->action(fn (Model $record) => $record->markAction($statusAction))
            ->after(fn (Model $record) => Notification::make()->success()->title('Success')
                ->body(fn () => class_basename($record) . " has been {$action}.")
                ->send()
            );
    }
}
