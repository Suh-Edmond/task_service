<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('public/auth')->group(function () {
    Route::post('/login', [UserController::class, 'loginUser']);
    Route::post('/register', [UserController::class, 'createUser']);
    Route::post('/logout', [UserController::class, 'logoutUser']);
});

Route::middleware('auth:sanctum')->group(function (){

    Route::prefix('protected/tasks')->group(function (){
       Route::post('/create', [TaskController::class, 'createTask']);
       Route::put('/{id}/update', [TaskController::class, 'updateTask']);
       Route::get('/users/{id}', [TaskController::class, 'fetchUserTasks']);
       Route::delete('{id}/users/{userId}', [TaskController::class, 'deleteTasks']);
    });
});
