<?php

use Illuminate\Support\Facades\Route;

Route::get("/weekly_stats",[\App\Http\Controllers\StatsViewController::class,"ViewWeekly"])
    ->middleware("is_notban","auth")
    ->name("weekly_stats");
