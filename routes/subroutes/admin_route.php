<?php

use Illuminate\Support\Facades\Route;

Route::get("/user_manage",[\App\Http\Controllers\AdminController::class,"AccountManager"])
    ->middleware("auth","admin")
    ->name("user_manage");

Route::get("/logs_manage",[\App\Http\Controllers\AdminController::class,"logs"])
    ->middleware("auth","admin")
    ->name("logs_manage");


Route::get("/filter_logs",[\App\Http\Controllers\AdminController::class,"logs"])
    ->middleware("auth","admin")
    ->name("filter_logs");


Route::post('/user/ban/{user}', [\App\Http\Controllers\AdminController::class,"banUser"])
    ->middleware("auth","admin")
    ->name('user.ban');
Route::post('/user/unban/{user}', [\App\Http\Controllers\AdminController::class,"unbanUser"])
    ->middleware("auth","admin")
    ->name('user.unban');
Route::patch('/user/reset-password/{user}', [\App\Http\Controllers\AdminController::class,"resetPassword"])
    ->middleware("auth","admin")
    ->name('user.reset-password');

Route::post('/admin/impersonate/{user}', [\App\Http\Controllers\AdminController::class, 'impersonate'])
    ->middleware('auth','admin')
    ->name('admin.impersonate');

Route::post('/admin/delete-user/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])
    ->middleware('auth','admin')
    ->name('admin.deleteUser');
