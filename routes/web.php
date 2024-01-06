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




Route::post("/add_folder",[\App\Http\Controllers\FolderController::class,"Store"])
    ->middleware("auth")
    ->name("add_folder");

Route::get("/view_folder/{id}",[\App\Http\Controllers\FolderController::class,"View"])
    ->middleware("auth")
    ->name("folder_view");

//</editor-fold>

//<editor-fold desc="Folder POST">



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

Route::get("/projet_view/{id}",[\App\Http\Controllers\ProjetController::class,"View"])
    ->middleware("auth")
    ->name("projet_view");

Route::post("/add_task_projet",[\App\Http\Controllers\ProjetController::class,"AddTask"])
    ->middleware("auth")
    ->name("add_task_projet");

Route::post("/store_projet",[\App\Http\Controllers\ProjetController::class,"Store"])
    ->middleware("auth")
    ->name("store_projet");

Route::post("/remove_task_from_project",[\App\Http\Controllers\ProjetController::class,"RemoveTaskFromProject"])
    ->middleware("auth")
    ->name("remove_task_from_project");


Route::post("/check_task_project",[\App\Http\Controllers\ProjetController::class,"CheckTaskTODO"])
    ->middleware("auth")
    ->name("check_task_project");

Route::post("/uncheck_task_project",[\App\Http\Controllers\ProjetController::class,"UncheckTaskDone"])
    ->middleware("auth")
    ->name("uncheck_task_project");
