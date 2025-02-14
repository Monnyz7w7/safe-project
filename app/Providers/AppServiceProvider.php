<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        FilamentColor::register([
            'primary' => Color::Purple,
            'gray' => Color::Slate,
            'success' => Color::Teal,
            'danger' => Color::Rose,
            'info' => Color::Indigo,
            'warning' => Color::Orange,
        ]);

        CreateAction::configureUsing(
            fn (CreateAction $action) => $action->slideOver()
        );

        EditAction::configureUsing(
            fn (EditAction $action) => $action->slideOver()
        );

        Select::configureUsing(
            fn (Select $component) => $component->native(false)
        );

        SelectFilter::configureUsing(
            fn (SelectFilter $component) => $component->native(false)
        );

        Table::configureUsing(
            fn (Table $component) => $component->filtersTriggerAction(
                fn ($action) => $action->button()->label('Filters')
            )
        );

        JsonResource::withoutWrapping();
    }
}
