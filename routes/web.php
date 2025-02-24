<?php

declare(strict_types=1);

use App\Enums\RouteNames;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TeamController::class, 'index'])
    ->name(RouteNames::DASHBOARD);

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


