<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::post('login', [UserController::class, 'login'])
    ->name('api.login');

Route::post('logout', [UserController::class, 'logout'])
    ->name('api.logout');

Route::post('/register', [UserController::class, 'register'])
    ->name('api.register');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'getUsers'])
            ->name('api.get_users');
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'getTasks'])
            ->name('api.get_tasks');

        Route::get('/priority', [TaskController::class, 'getTasksByPriority'])
            ->name('api.get_tasks_by_priority');

        Route::get('/{userId}', [TaskController::class, 'getTaskByUser'])
            ->name('api.get_tasks_by_user');

        Route::put('/change-task-status/{taskId}', [TaskController::class, 'changeTaskStatus'])
            ->name('api.change_task_status');

        Route::post('/create', [TaskController::class, 'createTask'])
            ->name('api.create_task');

        Route::put('/update/{taskId}', [TaskController::class, 'updateTask'])
            ->name('api.update_task');

        Route::delete('/delete/{taskId}', [TaskController::class, 'deleteTask'])
            ->name('api.delete_task');

        Route::post('/assign-task/{taskId}', [TaskController::class, 'assignTaskToUser'])
            ->name('api.assign_task');
    });
});
