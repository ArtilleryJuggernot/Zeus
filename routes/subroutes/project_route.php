<?php

use Illuminate\Support\Facades\Route;


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

Route::post("/unlink_task_from_project",[\App\Http\Controllers\ProjetController::class,"UnlinkTaskFromProject"])
    ->middleware(["is_notban","auth"])
    ->name("unlink_task_from_project");
