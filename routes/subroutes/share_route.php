<?php

use Illuminate\Support\Facades\Route;


Route::post("/add_note_share/",[\App\Http\Controllers\ShareController::class,"NoteStore"])
    ->middleware(["is_notban","auth"])
    ->name("add_note_share");


Route::post("/add_folder_share/",[\App\Http\Controllers\ShareController::class,"FolderStore"])
    ->middleware(["is_notban","auth"])
    ->name("add_folder_share");


Route::post("/add_task_share/",[\App\Http\Controllers\ShareController::class,"TacheStore"])
    ->middleware(["is_notban","auth"])
    ->name("add_task_share");


Route::post("/add_projet_share/",[\App\Http\Controllers\ShareController::class,"ProjetStore"])
    ->middleware(["is_notban","auth"])
    ->name("add_projet_share");

Route::post("/delete_perm/{id}",[\App\Http\Controllers\ShareController::class,"DeletePermById"])
    ->middleware(["is_notban","auth"])
    ->name("delete_perm");


