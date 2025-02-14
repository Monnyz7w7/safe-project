<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Forms;
use App\Enum\Status;
use Filament\Actions;
use App\Models\Report;
use Livewire\Attributes\Locked;
use App\Forms\Components\AvatarPreview;
use Facades\App\Services\DiscordLookUp;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportResource;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    #[Locked]
    public $userDiscordData = [];

    protected function getHeaderWidgets(): array
    {
        return [
            ReportResource\Widgets\ReportedUsersStatsWidget::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('lg')
                ->form([
                    Forms\Components\TextInput::make('discord_user_id')
                        ->required()
                        ->regex('/^\d{17,19}$/')
                        ->hiddenOn('edit'),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Fetch Discord Info')
                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                if (! preg_match('/^\d{17,19}$/', $get('discord_user_id'))) {
                                    return;
                                }

                                $this->userDiscordData = DiscordLookUp::find($get('discord_user_id'));

                                $set('discord_user_avatar_url', $this->userDiscordData['avatar_url'] ?? '');
                                $set('discord_username', $this->userDiscordData['username'] ?? '');
                                $set('discord_user_global_name', $this->userDiscordData['global_name'] ?? '');
                            })
                        ])
                        ->hiddenOn('edit'),

                    Forms\Components\Group::make()->schema([
                        AvatarPreview::make('discord_user_avatar_url')
                            ->view(view: 'forms.components.avatar-preview')
                            ->label('Avatar')
                            ->default('https://cdn.discordapp.com/embed/avatars/0.png')
                            ->hiddenLabel(),

                        Forms\Components\Group::make()->schema([
                            Forms\Components\TextInput::make('discord_username')
                                ->readOnly()
                                ->required()
                                ->label('Username'),

                            Forms\Components\TextInput::make('discord_user_global_name')
                                ->readOnly()
                                ->required()
                                ->label('Discord global name'),
                        ])
                        ->columns(2),

                        Forms\Components\RichEditor::make('details')
                            ->required()
                            ->string()
                            ->minLength(10)
                            ->maxLength(80000)
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link'
                            ]),

                        Forms\Components\Select::make('discord_server_id')
                            ->relationship('discordServer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                    ])
                    ->hidden(fn () => empty($this->userDiscordData))
                ])
                ->mutateFormDataUsing(fn (array $data) => $data + [
                    'user_id' => auth()->id()
                ])
                ->after(function (array $data, Report $record) {
                    $this->userDiscordData = [];

                    $record->markAction(Status::APPROVED);
                 })
        ];
    }
}
