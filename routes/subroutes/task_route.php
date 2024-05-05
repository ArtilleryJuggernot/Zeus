<?php

use Illuminate\Support\Facades\Route;


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
