<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});






use App\Http\Controllers\Api\NoteApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\FolderControllerApi;

Route::middleware(['auth:sanctum', 'throttle:1000,1'])->group(function () {
    Route::post('/notes/create', [NoteApiController::class, 'store']);
    Route::post('/admin/reset-password', [AdminApiController::class, 'resetUserPassword']);
    Route::patch('/notes/{id}/update', [NoteApiController::class, 'updateNote']);
    Route::get('/notes/folder/{folder_id}', [NoteApiController::class, 'getNotesByFolder']);
    Route::get('/folder/get_content/{folder_id}', [FolderControllerApi::class, 'getContent']);
});





Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    $user = User::where('email', $request->email)->firstOrFail();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Déconnexion réussie']);
});

