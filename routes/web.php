<?php

declare(strict_types=1);

use App\Enums\RouteNames;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TeamController::class, 'index'])
    ->name(RouteNames::DASHBOARD);

Route::group(['prefix' => 'teams'], function () {
    Route::group(['prefix' => '{index}/members'], function () {
        Route::post('{id}', [TeamMemberController::class, 'store'])
            ->name(RouteNames::TEAMS_MEMBERS_STORE);
        Route::delete('{id}', [TeamMemberController::class, 'delete'])
            ->name(RouteNames::TEAMS_MEMBERS_DELETE);
    });
    Route::post('add', [TeamController::class, 'add'])
        ->name(RouteNames::TEAMS_ADD);
    Route::delete('{index}', [TeamController::class, 'delete'])
        ->name(RouteNames::TEAMS_DELETE);
    Route::post('auto-allocate', [TeamController::class, 'autoAllocate'])
        ->name(RouteNames::TEAMS_AUTO_ALLOCATE);
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('', [SettingController::class, 'index'])
        ->name(RouteNames::SETTINGS_INDEX);
    Route::post('add', [SettingController::class, 'store'])
        ->name(RouteNames::SETTINGS_STORE);
});

Route::group(['prefix' => 'members'], function () {
    Route::get('refresh', [MemberController::class, 'refresh'])
        ->name(RouteNames::MEMBERS_REFRESH);
});


