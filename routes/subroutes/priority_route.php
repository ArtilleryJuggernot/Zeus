<?php

use Illuminate\Support\Facades\Route;

Route::post("/update-priority",[\App\Http\Controllers\PriorityController::class,"PriorityChange"])
    ->middleware(["is_notban","auth"])
    ->name("update-priority");
