<?php

namespace App\Livewire\User;

use App\Enum\Status;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Sponsorship;
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

class SponsorshipResource extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable,
        InteractsWithForms,
        InteractsWithActions;

    public ?array $data = [];

    public function mounted(Sponsorship $sponsorship)
    {
        $this->form->fill($sponsorship->toArray());
    }

    public function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(fn ($action) => $action->button()->label('Filters'))
            ->query(Sponsorship::query()
            ->where(function ($query) {
                $query->where('user_id', '!=', auth()->id())
                    ->where('status', Status::APPROVED)
                    ->orWhere(function ($query) {
                        $query->where('user_id', auth()->id());
                    });
            }))
            ->recordClasses(fn (Sponsorship $record) => $record->user_id === auth()->id() ? 'bg-gray-100' : '')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Registrant')
                    ->description(fn (Sponsorship $record) => $record->user->discord_id),

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
                    ->description(function (Sponsorship $record) {
                        if ($record->status === Status::UNDER_REVIEW) {
                            return;
                        }

                        return $record->action_at->diffForHumans();
                    })
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->native(false)
            ])
            ->actions([
                ActionGroup::make([
                    // EditAction::make()
                    //     ->modalWidth('3xl')
                    //     ->form(Sponsorship::filamentForm())
                    //     ->visible(fn (Sponsorship $record) => auth()->user()->can('update', $record)),

                    Tables\Actions\Action::make('Visit link')
                        ->requiresConfirmation()
                        ->action(fn (Sponsorship $record) => $this->js("window.open('{$record->link}')"))
                        ->icon('heroicon-m-cursor-arrow-rays'),

                    DeleteAction::make()
                        ->visible(fn (Sponsorship $record) => $record->isUnderReview())
                ])
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->form(Sponsorship::filamentForm())
                    ->mutateFormDataUsing(fn (array $data) => $data + [
                        'user_id' => auth()->id(),
                    ])
                    ->successNotificationTitle('Sponsorship has been submitted')
            ]);
    }

    public function render()
    {
        return view('livewire.user.sponsorship-resource');
    }
}
