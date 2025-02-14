<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enum\Status;
use Filament\Tables;
use App\Models\Report;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ReportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Filament\Resources\ReportResource\Widgets\ReportedUsersStatsWidget;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->groups([
                'discordServer.name',
                Tables\Grouping\Group::make('discord_username')->label('Reported User'),
                Tables\Grouping\Group::make('user.name')->label('Reporter'),
                Tables\Grouping\Group::make('actionBy.name')->label('Initator')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('discordServer.name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Report $record) => $record->discordServer->server_id),

                Tables\Columns\ViewColumn::make('discord_username')
                    ->searchable()
                    ->view('tables.columns.reported-user-with-avatar')
                    ->disableClick()
                    ->label('Reported User'),

                Tables\Columns\ViewColumn::make('user.name')
                    ->searchable()
                    ->view(view: 'tables.columns.reporter-with-avatar')
                    ->label('Reporter'),

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
                    ->options(Status::class),

                Tables\Filters\SelectFilter::make('server')
                    ->relationship('discordServer', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Report::filamentAction(action: 'approve', type: 'table'),
                    Report::filamentAction(action: 'decline', type: 'table')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
                        ->hidden(fn (Report $record) => $record->isUnderReview()),

                    Infolists\Components\TextEntry::make('action_at')
                        ->since()
                        ->label('Initiated')
                        ->hidden(fn (Report $record) => $record->isUnderReview())
                ])
                ->columns(3),

                Infolists\Components\Section::make('Reported User Details')->schema([
                    Infolists\Components\Group::make()->schema([
                        Infolists\Components\ViewEntry::make('discord_user_id')
                            ->view('infolists.components.reported-user-with-avatar')
                            ->hiddenLabel(),

                        Infolists\Components\TextEntry::make('details')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                ]),
            ])->columnSpan(['lg' => 2]),

            Infolists\Components\Group::make()->schema([
                Infolists\Components\Section::make('Server Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('discordServer.name')
                            ->label('Server name'),

                        Infolists\Components\TextEntry::make('discordServer.server_id')
                            ->label('Server ID'),

                        Infolists\Components\TextEntry::make('discordServer.invitation_link')
                            ->label('Invitation link')
                    ]),

                Infolists\Components\Section::make('Reporter')
                    ->schema([
                        Infolists\Components\ViewEntry::make('user.discord_username')
                            ->view('infolists.components.reporter-with-avatar')
                            ->hiddenLabel(),

                        Infolists\Components\TextEntry::make('created_at')
                            ->date()
                            ->label('Created'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last modified')
                            ->getStateUsing(fn (Report $record) => $record->updated_at->diffForHumans())
                    ]),
            ])
            ->columnSpan(['lg' => 1])
        ])
        ->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ReportResource\Widgets\ReportedUsersStatsWidget::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'view' => Pages\ViewReport::route('/{record}'),
        ];
    }
}
