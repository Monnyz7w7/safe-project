<?php

namespace App\Models;

use Filament\Forms;
use App\Enum\Status;
use App\Traits\HasStatus;
use App\Enum\SponsorshipType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sponsorship extends Model
{
    use HasFactory,
        HasStatus;

    protected $attributes = [
        'status' => Status::UNDER_REVIEW,
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'action_at' => 'datetime',
            'type' => SponsorshipType::class
        ];
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/' . $this->image_url) : 'https://images.pexels.com/photos/414974/pexels-photo-414974.jpeg?auto=compress&cs=tinysrgb&w=600'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function filamentForm()
    {
        return [
            Forms\Components\FileUpload::make('image_url')
                ->image()
                ->columnSpanFull()
                ->imageEditor()
                ->required()
                ->label('Image'),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('user_id')
                ->required()
                ->searchable()
                ->relationship('user', 'name')
                ->preload()
                ->label('Registrant')
                ->native(false)
                ->default(auth()->user()->id)
                ->visible(fn () => auth()->user()->can('create', Partnership::class)),

            Forms\Components\Textarea::make('description')
                ->required()
                ->columnSpanFull()
                ->rows(8),

            Forms\Components\TextInput::make('link')
                ->required()
                ->url()
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
                ->columnSpanFull()
                ->visible(fn () => auth()->user()->can('approve', Partnership::class)),

            Forms\Components\DateTimePicker::make('action_at')
                ->visible(fn () => auth()->user()->can('approve', Partnership::class)),
        ];
    }
}
