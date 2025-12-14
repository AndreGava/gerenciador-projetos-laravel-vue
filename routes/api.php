<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



// Rotas do projeto
Route::prefix('projects')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
});


//Rotas de tarefas
Route::prefix('tasks')->controller(TaskController::class)->group(function () {
    Route::post('/', 'store');
    Route::patch('/{task}/toggle', 'toggle');
    Route::delete('/{task}', 'destroy');
});
