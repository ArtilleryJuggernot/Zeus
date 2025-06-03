<?php


use Illuminate\Support\Facades\Route;

// Vue d'ensemble des habitudes (actives et désactivées)
Route::get('/habitude_overview', [\App\Http\Controllers\HabitudeController::class,'Overview'])
    ->middleware(['auth','is_notban'])
    ->name('habitude_overview');


// Vue d'édition d'une habitude
Route::get('/habitude_view/{id}', [\App\Http\Controllers\HabitudeController::class,"View"])
    ->middleware(['auth','is_notban'])
    ->name("habitude_view");


// Création d'une habitude
Route::post("/store_habitude",[\App\Http\Controllers\HabitudeController::class,"Store"])
    ->middleware(['auth','is_notban'])
    ->name("store_habitude");

// Activation/désactivation d'une habitude
Route::post("/toggle_habitude",[\App\Http\Controllers\HabitudeController::class,"Toggle"])
    ->middleware(['auth','is_notban'])
    ->name("toggle_habitude");

// Suppression d'une habitude
Route::post("/delete_habitude",[\App\Http\Controllers\HabitudeController::class,"Delete"])
    ->middleware(['auth','is_notban'])
    ->name("delete_habitude");

// Mise à jour d'une habitude
Route::post("/update_habitude",[\App\Http\Controllers\HabitudeController::class,"Update"])
    ->middleware(['auth','is_notban'])
    ->name("update_habitude");

