<?php
use Illuminate\Support\Facades\Route;



// GET

Route::get("/note_overview",[\App\Http\Controllers\NoteController::class,"OverView"])
    ->middleware(["is_notban","auth"])
    ->name("notes_overview");

Route::get('/note_view/{id}', [\App\Http\Controllers\NoteController::class,"View"])
    ->middleware('auth',"is_notban")
    ->name("note_view");


// POST

Route::post("/add_note",[\App\Http\Controllers\NoteController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("add_note");

Route::post('/save-note', [\App\Http\Controllers\NoteController::class, 'saveNote'])
    ->middleware(["is_notban","auth"])
    ->name('save.note');

Route::post("/delete_note",[\App\Http\Controllers\NoteController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_note");

Route::post("/downloadNote",[\App\Http\Controllers\NoteController::class,"Download"])
    ->middleware(["is_notban","auth"])
    ->name("downloadNote");
