<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Home
Route::get('/', function () {
    return view('welcome');
});

Route::get("/home",[\App\Http\Controllers\HomeController::class,'HomeView'])
->middleware("auth")->name("home");


Route::get("/about",[\App\Http\Controllers\HomeController::class,"AboutView"])
    ->name("about");


//<editor-fold desc="Folder GET">

Route::get("/folder_overview",[\App\Http\Controllers\FolderController::class,"OverView"])
    ->middleware("auth")
    ->name("folder_overview");

Route::get("/add_folder",[\App\Http\Controllers\FolderController::class,"Add"])
    ->middleware("auth")
    ->name("AddFolder");


Route::post("/add_folder",[\App\Http\Controllers\FolderController::class,"Store"])
    ->middleware("auth")
    ->name("add_folder");

Route::get("/view_folder/{id}",[\App\Http\Controllers\FolderController::class,"View"])
    ->middleware("auth")
    ->name("folder_view");

//</editor-fold>

//<editor-fold desc="Folder POST">

Route::post("/store_folder",[\App\Http\Controllers\FolderController::class,"Store2"])
    ->middleware("auth")
    ->name("store_folder");

Route::post("/delete_folder",[\App\Http\Controllers\FolderController::class,"Delete"])
    ->middleware("auth")
    ->name("delete_folder");


Route::post("/delete_note",[\App\Http\Controllers\NoteController::class,"Delete"])
    ->middleware("auth")
    ->name("delete_note");
//</editor-fold>



// Notes

Route::get("/note_overview",[\App\Http\Controllers\NoteController::class,"OverView"])
    ->middleware("auth")
    ->name("notes_overview");

Route::get("/add_note",[\App\Http\Controllers\NoteController::class,"Add"])
    ->middleware("auth")
    ->name("AddNote");


Route::post("/add_note",[\App\Http\Controllers\NoteController::class,"Store"])
    ->middleware("auth")
    ->name("add_note");

Route::get('/note_view/{id}', [\App\Http\Controllers\NoteController::class,"View"])
    ->middleware('auth')
    ->name("note_view");

Route::post('/save-note', [\App\Http\Controllers\NoteController::class, 'saveNote'])
    ->middleware("auth")
    ->name('save.note');


// Task

Route::get("/task_overview",[\App\Http\Controllers\TaskController::class,"OverView"])
    ->middleware("auth")
    ->name("task_overview");

Route::get("/view_task/{id}",[\App\Http\Controllers\TaskController::class,"View"])
    ->middleware("auth")
    ->name("view_task");

Route::get("/add_task",[\App\Http\Controllers\TaskController::class,"Add"])
    ->middleware("auth")
    ->name("AddTask");

Route::post("/store_task",[\App\Http\Controllers\TaskController::class,"Store"])
    ->middleware("auth")
    ->name("store_task");

Route::post("/save-task",[\App\Http\Controllers\TaskController::class,"Save"])
    ->middleware("auth");

Route::post("/delete_task",[\App\Http\Controllers\TaskController::class,"Delete"])
    ->middleware("auth")
    ->name("delete_task");
// Search

// HACK
Route::post("/do_search",[\App\Http\Controllers\SearchController::class,"doSearch"])
    ->middleware("auth");


// Projet

Route::get("/projet_overview",[\App\Http\Controllers\ProjetController::class,"Overview"])
    ->middleware("auth")
    ->name("projet_overview");

Route::get("/add_projet",[\App\Http\Controllers\ProjetController::class,"Add"])
    ->middleware("auth")
    ->name("AddProjet");

