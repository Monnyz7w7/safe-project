<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Partnership;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class PartnershipCreate extends Component implements HasActions, HasForms
{
    use InteractsWithActions,
        InteractsWithForms;

    public function createAction(): Action
    {
        return Action::make('create')
            ->label('Create Partnership')
            ->icon('heroicon-o-folder-plus')
            ->slideOver()
            ->modalWidth('xl')
            ->form(Partnership::filamentForm())
            ->action(function (array $data) {
                Partnership::create($data + ['user_id' => auth()->id()]);

                redirect(route('partnerships.index'));

                Notification::make()
                    ->success()
                    ->title('Partnership has been submitted')
                    ->body('Wait for approval while our staff reviews your request')
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.user.partnership-create');
    }
}
