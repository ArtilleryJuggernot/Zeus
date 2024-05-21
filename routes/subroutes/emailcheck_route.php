<?php

use Illuminate\Support\Facades\Route;

Route::get("/email/verify", function (){
    return view("auth.verify-email");
})->middleware(['auth'])->name('verification.notice');


Route::get("/email/verify/{id}/{hash}", function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request)
{
    $request->fulfill();
    return redirect("/");
}
)->middleware(['auth', 'signed'])->name('verification.verify');

Route::post("/email/verification-notification", function (\Illuminate\Http\Request $request)
{
 $request->user()->sendEmailVerificationNotification();
}
)->middleware(['auth', 'throttle:6,1'])->name('verification.send');
