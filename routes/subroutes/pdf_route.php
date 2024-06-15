<?php

use Illuminate\Support\Facades\Route;


Route::post("/note_pdf",[\App\Http\Controllers\PDFController::class,'DonwloadNote'])
    ->name("note_pdf");

