<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enum\Status;
use Filament\Tables\Table;
use App\Models\Sponsorship;
use App\Enum\SponsorshipType;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SponsorshipResource\Pages;
use App\Filament\Resources\SponsorshipResource\RelationManagers;

class SponsorshipResource extends Resource
{
    protected static ?string $model = Sponsorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->relationship('user', 'name')
                    ->preload()
                    ->label('Registrant')
                    ->default(auth()->user()->id),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->rows(8),

                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(255),

                Forms\Components\ToggleButtons::make('type')
                    ->required()
                    ->options(SponsorshipType::class)
                    ->inline(),

                Forms\Components\ToggleButtons::make('status')
                    ->required()
                    ->options(Status::class)
                    ->inline()
                    ->default(Status::UNDER_REVIEW)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state === Status::UNDER_REVIEW) {
                            return;
                        }

                        $status = is_string($state) ? Status::from($state) : $state;

                        $set('action_at', now()->toDateTimeString());
                    })
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('action_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->searchable()
                    ->circular()
                    ->label(''),

                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Registrant')
                    ->description(fn (Sponsorship $record) => $record->user->discord_id)
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('link')
                    ->wrap()
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->description(function (Sponsorship $record) {
                        if ($record->status === Status::UNDER_REVIEW) {
                            return;
                        }

                        return $record->action_at->diffForHumans();
                    }),

                Tables\Columns\TextColumn::make('actionBy.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Initiator'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last modified'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(SponsorshipType::class),

                Tables\Filters\SelectFilter::make('status')
                    ->options(Status::class)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('3xl'),

                    Sponsorship::filamentAction('approve'),

                    Sponsorship::filamentAction('decline'),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (Sponsorship $record) => $record->isUnderReview())
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
            'index' => Pages\ListSponsorships::route('/'),
        ];
    }
}
