<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Enum\Status;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->isUser();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('discord_username')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('discord_username')
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('discord_user_avatar_url')
                    ->circular()
                    ->label('Avatar'),

                Tables\Columns\TextColumn::make('discord_user_global_name')
                    ->searchable()
                    ->sortable()
                    ->label('Global name'),

                Tables\Columns\TextColumn::make('discord_username')
                    ->searchable()
                    ->sortable()
                    ->label('Username'),

                Tables\Columns\TextColumn::make('discord_user_id')
                    ->searchable()
                    ->sortable()
                    ->label('Discord ID'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->description(function (Report $record) {
                        if ($record->status === Status::UNDER_REVIEW) {
                            return;
                        }

                        return $record->action_at->diffForHumans();
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('actionBy.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Initiator'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Status::class)
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('View')
                        ->action(fn (Report $record) => redirect()->to(route('filament.admin.resources.reports.view', $record->id)))
                        ->icon('heroicon-m-eye'),
                    Report::filamentAction(action: 'approve', type: 'table'),
                    Report::filamentAction(action: 'decline', type: 'table')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
