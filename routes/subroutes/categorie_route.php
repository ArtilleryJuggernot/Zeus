<?php

use Illuminate\Support\Facades\Route;

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
