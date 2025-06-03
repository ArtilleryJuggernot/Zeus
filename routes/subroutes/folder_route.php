<?php

use App\Http\Controllers\FolderController;
use Illuminate\Support\Facades\Route;


Route::get("/folder_overview",[\App\Http\Controllers\FolderController::class,"OverView"])
    ->middleware(["is_notban","auth"])
    ->name("folder_overview");


Route::post("/add_folder",[\App\Http\Controllers\FolderController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("add_folder");

Route::get("/view_folder/{id}",[\App\Http\Controllers\FolderController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("folder_view");



Route::post("/delete_folder",[\App\Http\Controllers\FolderController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_folder");

Route::post("/downloadFolder",[FolderController::class,"Download"])
    ->middleware(["is_notban","auth"])
    ->name("downloadFolder");

Route::post('/folder-note-quick-update', [\App\Http\Controllers\FolderController::class, 'quickUpdate'])
    ->middleware(["is_notban","auth"])
    ->name('folder_note_quick_update');
