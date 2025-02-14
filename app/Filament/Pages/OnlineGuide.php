<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OnlineGuide extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.online-guide';

    protected static ?string $navigationGroup = 'Help';
}
