<?php

namespace App\Livewire\User;

use App\Enum\Status;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Partnership;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class PartnershipResource extends Component implements HasTable, HasForms, HasActions
{

    use InteractsWithTable,
        InteractsWithForms,
        InteractsWithActions;

    public ?array $data = [];

    public function mounted(Partnership $partnership)
    {
        $this->form->fill($partnership->toArray());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Registrant')
                    ->description(fn (Partnership $record) => $record->user->discord_id),

                ImageColumn::make('image_url')
                    ->label('Attached Image')
                    ->action(fn ($state, Component $livewire) => $livewire->js("window.open('storage/{$state}')")),

                TextColumn::make('title'),

                TextColumn::make('link')
                    ->wrap()
                    ->copyable(),

                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->description(function (Partnership $record) {
                        if ($record->status === Status::UNDER_REVIEW) {
                            return;
                        }

                        return $record->action_at->diffForHumans();
                    })
                    ->hidden(auth()->user()->partnership()->exists()),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->modalWidth('3xl')
                        ->form(Partnership::filamentForm())
                        ->visible(fn (Partnership $record) => auth()->user()->can('update', $record)),

                    Tables\Actions\Action::make('Visit link')
                        ->requiresConfirmation()
                        ->action(fn (Partnership $record) => $this->js("window.open('{$record->link}')"))
                        ->icon('heroicon-m-cursor-arrow-rays'),

                    DeleteAction::make()
                        ->visible(fn (Partnership $record) => $record->isUnderReview())
                ])
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->form(Partnership::filamentForm())
                    ->mutateFormDataUsing(fn (array $data) => $data + [
                        'user_id' => auth()->id(),
                    ])
                    ->successNotificationTitle('Partnership has been submitted')
                    ->visible(! auth()->user()->partnership()->exists())
            ]);
    }

    public function render()
    {
        return view('livewire.user.partnership-resource');
    }

    protected function getQuery()
    {
        $hasApprovedPartnerships = Partnership::query()->where('user_id', auth()->id())
            ->where('status', Status::APPROVED)
            ->exists();

        if ($hasApprovedPartnerships) {
            return Partnership::where('status', Status::APPROVED);
        }

        return Partnership::where('user_id', auth()->id());
    }
}
