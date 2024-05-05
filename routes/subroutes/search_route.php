<?php

use Illuminate\Support\Facades\Route;


Route::post("/do_search",[\App\Http\Controllers\SearchController::class,"doSearch"])
    ->middleware(["is_notban","auth"]);
