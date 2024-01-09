<?php

use Illuminate\Support\Facades\Route;
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
    Route::get('dashboard', [ErrorLogController::class, 'dashboard'])->name('dashboard');
    Route::get('view/{id}', [ErrorLogController::class, 'view'])->name('view');
    Route::post('clear-all', [ErrorLogController::class, 'clear'])->name('clear');
    Route::get('config', [ErrorLogController::class, 'config'])->name('config');
    Route::post('config', [ErrorLogController::class, 'config_store'])->name('config.store');
    Route::post('archive-selected', [ErrorLogController::class, 'archive_selected'])->name('archived');

    Route::group(['prefix' => 'archived', 'as' => 'archived.'], function() {
        Route::get('/', [ArchivedErrorLogController::class, 'index'])->name('index');
        Route::get('view/{id}', [ArchivedErrorLogController::class, 'view'])->name('view');
        Route::post('delete-selected', [ArchivedErrorLogController::class, 'destroy'])->name('delete-selected');
    });
    
});
