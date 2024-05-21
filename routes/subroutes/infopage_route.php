<?php

use Illuminate\Support\Facades\Route;

Route::get("/cgu",[\App\Http\Controllers\InfoPageController::class,'CGU'])
    ->name("CGU");
