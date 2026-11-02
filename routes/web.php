<?php

use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CrudController::class, 'dashboard'])->name('home');

Route::prefix('data')->name('crud.')->group(function () {
    Route::get('/{entity}', [CrudController::class, 'index'])->name('index');
    Route::get('/{entity}/create', [CrudController::class, 'create'])->name('create');
    Route::post('/{entity}', [CrudController::class, 'store'])->name('store');
    Route::get('/{entity}/{key}/edit', [CrudController::class, 'edit'])->name('edit');
    Route::put('/{entity}/{key}', [CrudController::class, 'update'])->name('update');
    Route::delete('/{entity}/{key}', [CrudController::class, 'destroy'])->name('destroy');
});
