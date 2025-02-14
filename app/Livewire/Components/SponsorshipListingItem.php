<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Sponsorship;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class SponsorshipListingItem extends Component implements HasActions, HasForms
{
    use InteractsWithForms,
        InteractsWithActions;

    public Sponsorship $sponsorship;

    public function editAction(): Action
    {
        return Action::make('edit')
            ->color('success')
            ->size('xs')
            ->icon('heroicon-o-pencil-square')
            ->slideOver()
            ->modalWidth('xl')
            ->fillForm(function (Sponsorship $sponsorship): array {
                return $this->sponsorship->only(
                    'image_url', 'title', 'description', 'link', 'type'
                );
            })
            ->form(Sponsorship::filamentForm())
            ->action(function (array $data) {
                abort_if(auth()->user()->cannot('update', $this->sponsorship), 403);

                $this->sponsorship->update($data);

                Notification::make()
                    ->success()
                    ->title('Sponsorship has been updated')
                    ->send();
            });
    }

    public function deleteAction()
    {
        return Action::make('delete')
            ->color('danger')
            ->size('xs')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function () {
                abort_if(auth()->user()->cannot('delete', $this->sponsorship), 403);

                $this->sponsorship->delete();

                $this->dispatch('sponsorship-deleted');

                Notification::make()
                    ->success()
                    ->title('Sponsorship has been deleted')
                    ->body("{$this->sponsorship->title} has been deleted.")
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.components.sponsorship-listing-item');
    }
}
