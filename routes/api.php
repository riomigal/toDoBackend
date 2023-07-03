<?php

use App\Api\Controllers\Auth\AuthController;
use App\Api\Controllers\Task\CategoryController;
use App\Api\Controllers\Task\PriorityController;
use App\Api\Controllers\Task\TaskController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('api.auth.login');
    Route::post('/register', 'register')->name('api.auth.register');
});

// Sanctum protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::post('store', 'store')->name('api.categories.store');
        Route::get('get', 'index')->name('api.categories.index');
        Route::delete('delete/{category}', 'delete')->name('api.categories.delete');
    });

    Route::prefix('tasks')->controller(TaskController::class)->group(function () {
        Route::post('store', 'store')->name('api.tasks.store');
        Route::get('get', 'index')->name('api.tasks.index');
        Route::put('update/{task}', 'update')->name('api.tasks.update');
        Route::delete('delete/{task}', 'delete')->name('api.tasks.delete');
        Route::post('complete/{task}', 'complete')->name('api.tasks.complete');
        Route::post('pending/{task}', 'pending')->name('api.tasks.pending');
    });

    Route::get('priorities/index', PriorityController::class)->name('api.priorities');

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});
