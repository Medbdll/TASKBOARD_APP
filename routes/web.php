<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('Task', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('Task/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('Task/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('Task/bulk-create', [TaskController::class, 'bulkCreate'])->name('tasks.bulk-create');
    Route::post('Task/bulk-store', [TaskController::class, 'bulkStore'])->name('tasks.bulk-store');
    Route::get('Task/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('Task/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('Task/{id}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::delete('Task/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('Task/trash', [TaskController::class, 'trash'])->name('tasks.trash');
    Route::post('Task/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::delete('Task/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');
});
require __DIR__ . '/auth.php';
