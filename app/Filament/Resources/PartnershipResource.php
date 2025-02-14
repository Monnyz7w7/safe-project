<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enum\Status;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Partnership;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PartnershipResource\Pages;
use App\Filament\Resources\PartnershipResource\RelationManagers;

class PartnershipResource extends Resource
{
    protected static ?string $model = Partnership::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema(Partnership::filamentForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(fn ($action) => $action->button()->label('Filters'))
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->circular()
                    ->label(''),

                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Registrant')
                    ->description(fn (Partnership $record) => $record->user->discord_id)
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('link')
                    ->wrap()
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->description(function (Partnership $record) {
                        if ($record->status === Status::UNDER_REVIEW) {
                            return;
                        }

                        return $record->action_at->diffForHumans();
                    }),

                Tables\Columns\TextColumn::make('actionBy.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Initiator'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last modified'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Status::class)
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('3xl'),

                    Tables\Actions\Action::make('Visit link')
                        ->requiresConfirmation()
                        ->action(fn (Partnership $record, Component $livewire) => $livewire->js("window.open('{$record->link}')"))
                        ->icon('heroicon-m-cursor-arrow-rays'),

                    Partnership::filamentAction(action: 'approve'),

                    Partnership::filamentAction(action: 'decline'),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (Partnership $record) => $record->isUnderReview())
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerships::route('/'),
        ];
    }
}
