<?php


use Illuminate\Support\Facades\Route;

Route::get('/habitude_overview', [\App\Http\Controllers\HabitudeController::class,'Overview'])
    ->middleware('auth',"is_notban")
    ->name('habitude_overview');


Route::get('/habitude_view/{id}', [\App\Http\Controllers\HabitudeController::class,"View"])
    ->middleware('auth',"is_notban")
    ->name("habitude_view");


Route::post("/store_habitude",[\App\Http\Controllers\HabitudeController::class,"Store"])
    ->middleware('auth',"is_notban")
    ->name("store_habitude");

Route::post("/toggle_habitude/",[\App\Http\Controllers\HabitudeController::class,"Toggle"])
    ->middleware('auth',"is_notban")
    ->name("toggle_habitude");

Route::post("/delete_habitude/",[\App\Http\Controllers\HabitudeController::class,"Delete"])
    ->middleware('auth',"is_notban")
    ->name("delete_habitude");

Route::post("/update_habitude/",[\App\Http\Controllers\HabitudeController::class,"Update"])
    ->middleware('auth',"is_notban")
    ->name("update_habitude");

