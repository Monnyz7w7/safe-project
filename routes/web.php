<?php

use Illuminate\Support\Facades\DB;
use App\Livewire\Pages\UserReports;
use App\Livewire\Pages\ReportSearch;
use App\Livewire\Pages\ReportDetails;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\EnsureUserIsApproved;
use App\Http\Middleware\RedirectUserBasedOnRole;
use App\Livewire\Pages\User\PartnershipListings;
use App\Livewire\Pages\User\SponsorshipListings;
use App\Http\Controllers\Socialite\AuthController;
use App\Http\Controllers\PendingApprovalController;

Route::redirect('/', 'login');
Route::redirect('/admin/login', '/');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    EnsureUserIsApproved::class,
    RedirectUserBasedOnRole::class
])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::resource('/reports', ReportController::class)->only('index');
    Route::get('reports/search', ReportSearch::class)->name('reports.search');
    Route::get('reports/search/{discordUserId}', UserReports::class)->name('reports.search.user');
    Route::get('reports/{report}', ReportDetails::class)->name('reports.show');
    Route::get('/partnerships', PartnershipListings::class)->name('partnerships.index');
    Route::get('/sponsorships', SponsorshipListings::class)->name('sponsorships.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'index'])->name('auth.index');
    Route::get('/auth/redirect/{service}', [AuthController::class, 'redirect'])->name('auth.redirect');
    Route::get('/auth/callback/{service}', [AuthController::class, 'callback'])->name('auth.callback');
});

Route::get('/pending-approval', PendingApprovalController::class)->name('pending-approval');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    EnsureUserIsApproved::class
])->get('discord-bot-secret-api-token', function () {
    $user = request()->user();

    if ($userToken = $user->tokens->first()) {
        $token = $userToken->token;
    } else {
        $token = $user->createToken('discord-bot')->plainTextToken;
    }

    return response()->json(['token' => $token]);
});
