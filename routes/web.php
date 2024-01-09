<?php

use Illuminate\Support\Facades\Route;
use Narolalabs\ErrorLens\Http\Controllers\ConfigurationController;
use Narolalabs\ErrorLens\Http\Controllers\ErrorLogController;
use Narolalabs\ErrorLens\Http\Controllers\ArchivedErrorLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'error-lens', 'as' => 'error-lens.', 'middleware' => ['web', 'basicAuth']], function() {
    Route::get('/', [ErrorLogController::class, 'index'])->name('index');
    Route::post('/', [ErrorLogController::class, 'index'])->name('index.search');
    Route::get('dashboard', [ErrorLogController::class, 'dashboard'])->name('dashboard');
    Route::get('view/{id}', [ErrorLogController::class, 'view'])->name('view');
    Route::post('clear-all', [ErrorLogController::class, 'clear'])->name('clear');
    Route::post('archive-selected', [ErrorLogController::class, 'archive_selected'])->name('archived');

    Route::get('config', [ConfigurationController::class, 'config'])->name('config');
    Route::post('config', [ConfigurationController::class, 'config_store'])->name('config.store');
    Route::post('clear-cache', [ConfigurationController::class, 'cache_clear'])->name('config.cache-clear');

    Route::group(['prefix' => 'archived', 'as' => 'archived.'], function() {
        Route::get('/', [ArchivedErrorLogController::class, 'index'])->name('index');
        Route::post('/', [ArchivedErrorLogController::class, 'index'])->name('index.search');
        Route::get('view/{id}', [ArchivedErrorLogController::class, 'view'])->name('view');
        Route::post('delete-selected', [ArchivedErrorLogController::class, 'destroy'])->name('delete-selected');
    });
    
});
