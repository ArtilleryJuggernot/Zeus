<?php

use Illuminate\Support\Facades\Route;

Route::get("/profile/{id}",[\App\Http\Controllers\ProfilController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("profile");

Route::post("/update_password",[\App\Http\Controllers\ProfilController::class,"ChangePassword"])
    ->middleware(["is_notban","auth"])
    ->name("update_password");
