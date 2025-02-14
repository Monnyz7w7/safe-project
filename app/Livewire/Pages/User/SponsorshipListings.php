<?php

namespace App\Livewire\Pages\User;

use App\Enum\Status;
use Livewire\Component;
use App\Models\Sponsorship;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class SponsorshipListings extends Component implements HasActions, HasForms
{
    use InteractsWithForms,
        InteractsWithActions,
        WithPagination;

    const ITEMS_PER_PAGE = 12;

    #[On('sponsorship-deleted')]
    public function refreshComponent()
    {
        //
    }

    public function createAction()
    {
        return Action::make('create')
            ->label('Create Partnership')
            ->icon('heroicon-o-folder-plus')
            ->slideOver()
            ->modalWidth('xl')
            ->form(Sponsorship::filamentForm())
            ->action(function (array $data) {
                abort_unless(auth()->user()->canCreateSponsorship(), 429);

                Sponsorship::create($data + ['user_id' => auth()->id()]);

                Notification::make()
                    ->success()
                    ->title('Sponsorship has been submitted')
                    ->body('Wait for approval while our staff reviews your request')
                    ->send();
            });
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.user.sponsorship-listings', [
            'sponsorships' => Sponsorship::with('user')
                ->where(function (Builder $query) {
                    $query->where('user_id', '!=', auth()->id())
                        ->where('status', Status::APPROVED)
                        ->orWhere(function (Builder $query) {
                            $query->where('user_id', auth()->id());
                    });
                })
                ->orderByRaw("user_id = ? DESC", [auth()->id()])
                ->orderBy('status', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(self::ITEMS_PER_PAGE)
        ]);
    }
}
