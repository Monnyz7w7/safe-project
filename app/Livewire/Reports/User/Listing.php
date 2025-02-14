<?php

namespace App\Livewire\Reports\User;

use Filament\Forms;
use App\Enum\Status;
use App\Models\Report;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Livewire\Attributes\Locked;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Contracts\HasForms;
use App\Forms\Components\AvatarPreview;
use Facades\App\Services\DiscordLookUp;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class Listing extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable,
        InteractsWithForms,
        InteractsWithActions;

    public ?array $data = [];

    #[Locked]
    public $userDiscordData = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(Report::query()->where('user_id', auth()->id()))
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('discord_user_avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->default('https://cdn.discordapp.com/embed/avatars/0.png'),

                TextColumn::make('discord_username')
                    ->searchable()
                    ->label('Username'),

                TextColumn::make('discord_user_global_name')
                    ->searchable()
                    ->label('Name'),

                TextColumn::make('details')
                    ->formatStateUsing(fn ($state): ?string => Str::of($state)->stripTags()->limit(100))
                    ->wrap(),

                TextColumn::make('status')
                    ->badge()
                    ->color(color: fn ($state): string => $state->getColor())
                    ->icon(fn ($state): string => $state->getIcon())
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Created'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->native(false)
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->form($this->formFields())
                    ->hidden(fn (Report $record): bool => ($record->isApproved() || $record->isDeclined()))
            ])
            ->bulkActions([
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->form($this->formFields())
                    ->mutateFormDataUsing(fn (array $data) => $data + [
                        'user_id' => auth()->id(),
                        'discord_server_id' => auth()->user()->discordServer->id
                    ])
                    ->label('Report a user')
                    ->successNotificationTitle('User has been reported')
                    ->after(fn (array $data) => $this->userDiscordData = null)
            ]);
    }

    public function render()
    {
        return view('livewire.reports.user.listing');
    }

    protected function formFields(): array
    {
        return [
            TextInput::make('discord_user_id')
                ->required()
                ->regex('/^\d{17,19}$/')
                ->hiddenOn('edit'),

            Actions::make([
                Forms\Components\Actions\Action::make('Fetch Discord Info')
                    ->action(function (Get $get, Set $set) {
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

            Group::make()->schema([
                AvatarPreview::make('discord_user_avatar_url')
                    ->view(view: 'forms.components.avatar-preview')
                    ->label('Avatar')
                    ->default('https://cdn.discordapp.com/embed/avatars/0.png')
                    ->hiddenLabel(),

                Group::make()->schema([
                    TextInput::make('discord_username')
                        ->readOnly()
                        ->label('Username'),

                    TextInput::make('discord_user_global_name')
                        ->readOnly()
                        ->label('Discord global name'),
                ])
                ->columns(2),

                RichEditor::make('details')
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
                    ])
            ])
            ->hidden(function (string $operation) {
                if ($operation === 'edit') {
                    return false;
                }

                return is_null($this->userDiscordData);
            })
        ];
    }
}
