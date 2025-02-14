<?php

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DiscordServerController;
use App\Http\Controllers\Api\FetchDiscordUserInfoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::get('/user', fn () => UserResource::make(request()->user()->load('actionBy')));

    Route::get('/discord-servers', [DiscordServerController::class, 'index']);
    Route::get('/discord-servers/{discordServer}', [DiscordServerController::class, 'show']);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{report}', [ReportController::class, 'show']);
    Route::post('/reports', [ReportController::class, 'store']);

    Route::get('fetch-discord-user-info', FetchDiscordUserInfoController::class);
});
