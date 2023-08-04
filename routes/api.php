<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/current', [UserController::class, 'showCurrent']);
    Route::patch('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'delete']);

    Route::post('todos/', [TodoController::class, 'store']);
    Route::get('todos/', [TodoController::class, 'index']);
    Route::get('todos/{todo}', [TodoController::class, 'show']);
    Route::patch('todos/{todo}', [TodoController::class, 'update']);
    Route::delete('todos/{todo}', [TodoController::class, 'destroy']);

    Route::post('tasks/', [TaskController::class, 'store']);
    Route::get('tasks/', [TaskController::class, 'index']);
    Route::get('tasks/by_todo/{todo_id}', [TaskController::class, 'showByTodo']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::post('tasks/tags_search', [TaskController::class, 'searchByTags']);
    Route::post('tasks/search', [TaskController::class, 'search']);
    Route::patch('tasks/{task}', [TaskController::class, 'update']);
    Route::patch('tasks/add_image/{task}', [TaskController::class, 'addImage']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);

    Route::post('tags/', [TagController::class, 'store']);
    Route::get('tags/unique', [TagController::class, 'unique']);
    Route::get('tags/', [TagController::class, 'index']);
    Route::get('tags/{tag}', [TagController::class, 'show']);
    Route::get('tags/by_task/{task_id}', [TagController::class, 'showByTask']);
    Route::patch('tags/{tag}', [TagController::class, 'update']);
    Route::delete('tags/{tag}', [TagController::class, 'destroy']);
});
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
