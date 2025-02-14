<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enum\Status;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DiscordServer;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DiscordServerResource\Pages;
use App\Filament\Resources\DiscordServerResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class DiscordServerResource extends Resource
{
    protected static ?string $model = DiscordServer::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereHas(
            'user', fn (Builder $query) => $query->whereStatus(Status::APPROVED)
        )->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', fn (Builder $query) => $query->whereStatus(Status::APPROVED));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('server_id')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('invitation_link')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(
                        query: fn ($query, $search) => $query->where('name', 'like', "%{$search}%")
                            ->orWhere('server_id', 'like', "%{$search}%")
                    )
                    ->sortable()
                    ->description(fn (DiscordServer $record) =>$record->server_id),

                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->description(fn (DiscordServer $record) => $record->user->discord_username)
                    ->label('Registrant'),

                Tables\Columns\TextColumn::make('invitation_link')
                    ->copyable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('reports_count')
                    ->counts('reports')
                    ->label('Total Reports'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Created'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last modified'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                //     ->slideOver()
                //     ->modalWidth('lg'),
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
            'index' => Pages\ListDiscordServers::route('/'),
            // 'create' => Pages\CreateDiscordServer::route('/create'),
            // 'edit' => Pages\EditDiscordServer::route('/{record}/edit'),
        ];
    }
}
