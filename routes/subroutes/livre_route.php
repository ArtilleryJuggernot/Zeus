<?php

use Illuminate\Support\Facades\Route;


Route::get("/livre_overview",[\App\Http\Controllers\LivreController::class,"Overview"])
    ->middleware("auth","is_notban")
    ->name("livre_overview");


Route::post("/store_livre,",[\App\Http\Controllers\LivreController::class,"Store"])
    ->middleware("auth","is_notban")
    ->name("store_livre");

