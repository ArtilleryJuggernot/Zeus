<?php
use Illuminate\Support\Facades\Route;


Route::get('auth/gmail', [\App\Http\Controllers\GmailLoginController::class,'redirectToGmail'])
    ->name('google.redirect');
Route::get('auth/gmail/callback', [\App\Http\Controllers\GmailLoginController::class,'handleGmailCallback'])
    ->name('google.callback');;
