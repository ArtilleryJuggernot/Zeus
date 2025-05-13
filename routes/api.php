<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



use App\Http\Controllers\Api\McpController;

Route::middleware('auth:sanctum')->group(function () {
    // Exemple de route pour récupérer toutes les tâches
    Route::get('/tasks', [McpController::class, 'getTasks']);

    // Exemple de route pour récupérer une tâche par son ID
    Route::get('/task/{id}', [McpController::class, 'getTaskById']);

    // Exemple de route pour mettre à jour une tâche
    Route::put('/task/{id}', [McpController::class, 'updateTask']);

    // Exemple de route pour supprimer une tâche
    Route::delete('/task/{id}', [McpController::class, 'deleteTask']);

    // Exemple de route pour créer une nouvelle ressource (note, projet, etc.)
    Route::post('/resource', [McpController::class, 'createResource']);
});
