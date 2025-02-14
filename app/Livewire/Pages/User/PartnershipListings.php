<?php

namespace App\Livewire\Pages\User;

use App\Enum\Status;
use Livewire\Component;
use App\Models\Partnership;
use Filament\Actions\Action;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PartnershipListings extends Component implements HasActions, HasForms
{
    use InteractsWithActions,
        InteractsWithForms,
        WithPagination;

    const ITEMS_PER_PAGE = 12;

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

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.pages.user.partnership-listings', [
            'partnerships' => $this->getQuery()
        ]);
    }

    protected function getQuery(): LengthAwarePaginator
    {
        $hasApprovedPartnerships = Partnership::query()->where('user_id', auth()->id())
            ->where('status', Status::APPROVED)
            ->exists();

        if ($hasApprovedPartnerships) {
            return Partnership::with('user')
                ->where('status', Status::APPROVED)
                ->latest('id')
                ->paginate(self::ITEMS_PER_PAGE);
        }

        return Partnership::with('user')
            ->where('user_id', auth()->id())
            ->where('status', '<>', Status::DECLINED)
            ->latest('id')
            ->paginate(self::ITEMS_PER_PAGE);
    }
}
