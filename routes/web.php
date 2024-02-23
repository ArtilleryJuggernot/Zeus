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


// Home view welcome
Route::get('/', function () {
    return redirect()->route("home");
});

Route::get("/home",[\App\Http\Controllers\HomeController::class,'HomeView'])
->middleware(["is_notban","auth"])
    ->name("home");

Route::get("/banned",function (){
    return view("bannis");
})->name("banned");

Route::get("/about",[\App\Http\Controllers\HomeController::class,"AboutView"])
    ->middleware(["is_notban","auth","admin"])
    ->name("about");


//<editor-fold desc="Folder GET">

Route::get("/folder_overview",[\App\Http\Controllers\FolderController::class,"OverView"])
    ->middleware(["is_notban","auth"])
    ->name("folder_overview");


Route::post("/add_folder",[\App\Http\Controllers\FolderController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("add_folder");

Route::get("/view_folder/{id}",[\App\Http\Controllers\FolderController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("folder_view");

//</editor-fold>

//<editor-fold desc="Folder POST">



Route::post("/delete_folder",[\App\Http\Controllers\FolderController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_folder");


Route::post("/delete_note",[\App\Http\Controllers\NoteController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_note");
//</editor-fold>



// Notes

Route::get("/note_overview",[\App\Http\Controllers\NoteController::class,"OverView"])
    ->middleware(["is_notban","auth"])
    ->name("notes_overview");

Route::post("/add_note",[\App\Http\Controllers\NoteController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("add_note");

Route::get('/note_view/{id}', [\App\Http\Controllers\NoteController::class,"View"])
    ->middleware('auth',"is_notban")
    ->name("note_view");

Route::post('/save-note', [\App\Http\Controllers\NoteController::class, 'saveNote'])
    ->middleware(["is_notban","auth"])
    ->name('save.note');


// Task

Route::get("/task_overview",[\App\Http\Controllers\TaskController::class,"OverView"])
    ->middleware(["is_notban","auth"])
    ->name("task_overview");


Route::get("/task_overview_project",[\App\Http\Controllers\TaskController::class,"OverviewTaskProject"])
    ->middleware(["is_notban","auth"])
    ->name("task_overview_project");

Route::get("/view_task/{id}",[\App\Http\Controllers\TaskController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("view_task");


Route::post("/store_task",[\App\Http\Controllers\TaskController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("store_task");

Route::post("/save-task",[\App\Http\Controllers\TaskController::class,"Save"])
    ->middleware(["is_notban","auth"]);

Route::post("/delete_task",[\App\Http\Controllers\TaskController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_task");

Route::post("/UpdateTaskStatus",[\App\Http\Controllers\TaskController::class,"UpdateFinishStatus"])
    ->middleware(["is_notban","auth"])
    ->name("UpdateTaskStatus");

// Search


Route::post("/do_search",[\App\Http\Controllers\SearchController::class,"doSearch"])
    ->middleware(["is_notban","auth"]);


// Projet

Route::get("/projet_overview",[\App\Http\Controllers\ProjetController::class,"Overview"])
    ->middleware(["is_notban","auth"])
    ->name("projet_overview");

Route::get("/projet_view/{id}",[\App\Http\Controllers\ProjetController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("projet_view");

Route::post("/add_task_projet",[\App\Http\Controllers\ProjetController::class,"AddTask"])
    ->middleware(["is_notban","auth"])
    ->name("add_task_projet");

Route::post("/store_projet",[\App\Http\Controllers\ProjetController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("store_projet");

Route::post("/remove_task_from_project",[\App\Http\Controllers\ProjetController::class,"RemoveTaskFromProject"])
    ->middleware(["is_notban","auth"])
    ->name("remove_task_from_project");


Route::post("/check_task_project",[\App\Http\Controllers\ProjetController::class,"CheckTaskTODO"])
    ->middleware(["is_notban","auth"])
    ->name("check_task_project");

Route::post("/uncheck_task_project",[\App\Http\Controllers\ProjetController::class,"UncheckTaskDone"])
    ->middleware(["is_notban","auth"])
    ->name("uncheck_task_project");


Route::post("/delete_project/",[\App\Http\Controllers\ProjetController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_project");


Route::post("/archive_project/",[\App\Http\Controllers\ProjetController::class,"CheckToggleAsDone"])
    ->middleware(["is_notban","auth"])
    ->name("archive_project");


Route::post("/add_existing_to_project",[\App\Http\Controllers\ProjetController::class,"AddExistingTaskToProject"])
    ->middleware(["is_notban","auth"])
    ->name("add_existing_to_project");



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


// Categorie

Route::get("/categorie_overview/",[\App\Http\Controllers\CategorieController::class,"Overview"])
    ->middleware(["is_notban","auth"])
    ->name("categorie_overview");

Route::post("/delete_categorie/",[\App\Http\Controllers\CategorieController::class,"Delete"])
    ->middleware(["is_notban","auth"])
    ->name("delete_categorie");

Route::post("/store_categorie/",[\App\Http\Controllers\CategorieController::class,"Store"])
    ->middleware(["is_notban","auth"])
    ->name("store_categorie");


Route::post("/addCategory/",[\App\Http\Controllers\CategorieController::class,"AddCategorieToRessource"])
    ->middleware(["is_notban","auth"])
    ->name("addCategory");


Route::post("/removeCategory/",[\App\Http\Controllers\CategorieController::class,"RemoveCategorieToRessource"])
    ->middleware(["is_notban","auth"])
    ->name("removeCategory");

Route::post("/searchCategory/",[\App\Http\Controllers\CategorieController::class,"Search"])
    ->middleware(["is_notban","auth"])
    ->name("searchCategory");


// Profil

Route::get("/profile/{id}",[\App\Http\Controllers\ProfilController::class,"View"])
    ->middleware(["is_notban","auth"])
    ->name("profile");

Route::post("/update_password",[\App\Http\Controllers\ProfilController::class,"ChangePassword"])
    ->middleware(["is_notban","auth"])
    ->name("update_password");

// Admin

Route::get("/user_manage",[\App\Http\Controllers\AdminController::class,"AccountManager"])
    ->middleware("auth","admin")
    ->name("user_manage");

Route::get("/logs_manage",[\App\Http\Controllers\AdminController::class,"logs"])
    ->middleware("auth","admin")
    ->name("logs_manage");


Route::get("/filter_logs",[\App\Http\Controllers\AdminController::class,"logs"])
    ->middleware("auth","admin")
    ->name("filter_logs");

// Priorité tâche

Route::post("/update-priority",[\App\Http\Controllers\PriorityController::class,"PriorityChange"])
    ->middleware(["is_notban","auth"])
    ->name("update-priority");



Route::get("/weekly_stats",[\App\Http\Controllers\StatsViewController::class,"ViewWeekly"])
    ->middleware("is_notban","auth")
    ->name("weekly_stats");

// routes/web.php


Route::post('/user/ban/{user}', [\App\Http\Controllers\AdminController::class,"banUser"])->name('user.ban');
Route::post('/user/unban/{user}', [\App\Http\Controllers\AdminController::class,"unbanUser"])->name('user.unban');
Route::patch('/user/reset-password/{user}', [\App\Http\Controllers\AdminController::class,"resetPassword"])->name('user.reset-password');

