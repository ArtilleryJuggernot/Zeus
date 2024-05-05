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
    ->middleware(["is_notban","auth"])
    ->name("about");



// Test mail


Route::get("/mailtest",[\App\Http\Controllers\MailController::class,'sendMail'])
    ->middleware("is_notban","auth","admin")
    ->name("mailtest");


// Notes
require "subroutes/note_route.php";


// Folder
require "subroutes/folder_route.php";


// Task
require "subroutes/task_route.php";


// Search
require "subroutes/search_route.php";

// Projet
require "subroutes/project_route.php";


// Share
require "subroutes/share_route.php";


// Categorie
require "subroutes/categorie_route.php";


// Profil
require "subroutes/profil_route.php";

// Admin
require "subroutes/admin_route.php";

// Priorité tâche
require "subroutes/priority_route.php";

// Statistiques
require "subroutes/stats_route.php";


// Module : Livre
require "subroutes/livre_route.php";

require "subroutes/api_route.php";

// Gmail Login

require  "subroutes/gmail_route.php";


// 
