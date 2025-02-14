<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\OnlineCourse;

class OnlineCourses extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.pages.online-courses';

    protected static ?string $navigationGroup = 'Help';

    public function getCourseUrl()
    {
        $course = OnlineCourse::whereRole(auth()->user()->role)->first();

        return $course?->url;
    }
}
