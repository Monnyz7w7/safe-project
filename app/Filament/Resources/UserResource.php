<?php

namespace App\Filament\Resources;

use App\Enum\Role;
use Filament\Forms;
use App\Enum\Status;
use App\Models\User;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        if (! auth()->user()->isAdmin()) {
            return parent::getEloquentQuery()->whereRole('user');
        }

        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\ToggleButtons::make('role')
                            ->enum(enum: Role::class)
                            ->options(options: Role::class)
                            ->inline()
                            ->live()
                            ->default(Role::USER)
                            ->disabled(fn (?User $record) => $record?->id === auth()->id() || $record?->id === 1),

                        Forms\Components\FileUpload::make('profile_photo_path')
                            ->avatar()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1'
                            ])
                            ->imageEditorMode(1)
                            ->label('Avatar')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('discord_id')
                            ->numeric()
                            ->maxLength(255)
                            ->label('Discord ID'),

                        Forms\Components\TextInput::make('discord_username')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $context) => $context === 'create')
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state)),

                        Forms\Components\Fieldset::make('Discord Server Details')
                            ->relationship('discordServer')
                            ->schema([
                                Forms\Components\TextInput::make('server_id')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(255)
                                    ->label('Server ID'),

                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->string()
                                    ->maxLength(255)
                                    ->label('Server Name'),

                                Forms\Components\TextInput::make('invitation_link')
                                    ->required()
                                    ->url()
                                    ->maxLength(255)
                            ])
                            ->columns(1)
                            ->hidden(fn (Forms\Get $get): bool => $get('role') !== Role::USER),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn (User $record): HtmlString => new HtmlString('<span class="text-xs">'. $record->discord_id .'</span>')),

                    Tables\Columns\TextColumn::make('discord_username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('discord_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Discord User ID'),

                Tables\Columns\TextColumn::make('discordServer.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Server'),

                Tables\Columns\TextColumn::make('reports_count')
                    ->counts('reports')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Total Reports'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('actionBy.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Initiator'),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->searchable()
                    ->color(color: fn ($state): string => $state->getColor())
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(auth()->user()->isAdmin()),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Created'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last Updated'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(Role::class)
                    ->multiple(),

                Tables\Filters\SelectFilter::make('server')
                    ->relationship('discordServer', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth('lg'),

                    User::filamentAction(action: 'approve', type: 'table'),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (User $record) => auth()->user()->isAdmin() || $record->id !== auth()->id()),
                ]),

                Impersonate::make()->visible(fn (User $record) => auth()->user()->isAdmin() && $record->isApproved())
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Approve')
                        ->requiresConfirmation()
                        ->action(
                            fn (Collection $records) => $records->each->markAction(Status::APPROVED)
                        )
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->modalHeading('Approve Selected Users')
                        ->deselectRecordsAfterCompletion()
                        ->after(fn () => Notification::make()->success()->title('Success')
                            ->body(new HtmlString('Selected users has been approved.'))
                            ->send()
                        )
                        ->visible(fn (User $record) => auth()->user()->can('approve', $record)),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $record): bool => $record->status === Status::UNDER_REVIEW)
            ->selectCurrentPageOnly();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Group::make()->schema([
                Infolists\Components\Section::make()->schema([
                    Infolists\Components\TextEntry::make('status')
                        ->badge(),

                    Infolists\Components\TextEntry::make('actionBy.name')
                        ->label('Initiated by')
                        ->hidden(fn (User $record) => $record->isUnderReview()),

                    Infolists\Components\TextEntry::make('action_at')
                        ->since()
                        ->label('Initiated')
                        ->hidden(fn (User $record) => $record->isUnderReview())
                ])
                ->columns(3),

                Infolists\Components\Section::make('User Details')->schema([
                    Infolists\Components\ImageEntry::make('avatar')
                        ->circular()
                        ->size(100)
                        ->hiddenLabel()
                        ->columnSpanFull(),

                    Infolists\Components\TextEntry::make('discord_id')
                        ->label('Discord User ID')
                        ->copyable(),

                    Infolists\Components\TextEntry::make('name'),

                    Infolists\Components\TextEntry::make('email')
                        ->copyable(),

                    Infolists\Components\TextEntry::make('discord_username'),
                ])
                ->columns(2),
            ])->columnSpan(['lg' => 2]),

            Infolists\Components\Group::make()->schema([
                Infolists\Components\Section::make('Server Details')->schema([
                    Infolists\Components\TextEntry::make('discordServer.server_id')
                        ->label('Discord server ID')
                        ->default('Not yet provided'),

                    Infolists\Components\TextEntry::make('discordServer.name')
                        ->label('Discord name')
                        ->default('Not yet provided'),

                    Infolists\Components\TextEntry::make('discordServer.invitation_link')
                        ->label('Discord invitation link')
                        ->copyable(fn ($state) => Str::isUrl($state))
                        ->copyMessage('Copied to clipboard!')
                        ->default('Not yet provided'),
                ]),

                Infolists\Components\Section::make()->schema([
                    Infolists\Components\TextEntry::make('created_at')
                        ->since()
                        ->label('Created'),

                    Infolists\Components\TextEntry::make('updated_at')
                        ->since()
                        ->label('Last modified'),
                ])
                ->columns(2)
            ])
            ->columnSpan(['lg' => 1]),
        ])
        ->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            UserResource\RelationManagers\ReportsRelationManager::class
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UserResource\Widgets\UserStatsWidget::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
