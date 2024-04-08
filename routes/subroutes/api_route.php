<?php
use Illuminate\Support\Facades\Route;

Route::post("/api/update-label",[\App\Http\Controllers\RefactoringController::class,"Refactoring"])
    ->middleware(["is_notban","auth"])
    ->name("update-label");
