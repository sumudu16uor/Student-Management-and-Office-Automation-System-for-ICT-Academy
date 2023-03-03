<?php

use App\Http\Controllers\ProcessController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('ProcessManagement')
    ->as('processes.')
    ->group(function (){
        Route::get('/processes', [ProcessController::class, 'index'])->name('index');
        Route::get('/processes/showMonthEnd', [ProcessController::class, 'showMonthEnd'])->name('show.monthEnd');
        Route::get('/processes/showYearEnd', [ProcessController::class, 'showYearEnd'])->name('show.yearEnd');
        Route::get('/processes/showOrdinaryLevelEnd', [ProcessController::class, 'showOrdinaryLevelEnd'])->name('show.ordinaryLevelEnd');
        Route::get('/processes/showAdvancedLevelEnd', [ProcessController::class, 'showAdvancedLevelEnd'])->name('show.advancedLevelEnd');
        Route::get('/processes/showClearLogin', [ProcessController::class, 'showClearLogin'])->name('show.clearLogin');
        Route::get('/processes/{process:processID}', [ProcessController::class, 'show'])->name('show');
        Route::post('/processes/monthEnd', [ProcessController::class, 'monthEnd'])->name('store.monthEnd');
        Route::post('/processes/yearEnd', [ProcessController::class, 'yearEnd'])->name('store.yearEnd');
        Route::post('/processes/ordinaryLevelEnd', [ProcessController::class, 'ordinaryLevelEnd'])->name('store.ordinaryLevelEnd');
        Route::post('/processes/advancedLevelEnd', [ProcessController::class, 'advancedLevelEnd'])->name('store.advancedLevelEnd');
        Route::post('/processes/clearLogin', [ProcessController::class, 'clearLogin'])->name('store.clearLogin');
        Route::post('/processes/{process:processID}/reverseMonthEnd', [ProcessController::class, 'reverseMonthEnd'])->name('reverseMonthEnd');
        Route::post('/processes/{process:processID}/reverseYearEnd', [ProcessController::class, 'reverseYearEnd'])->name('reverseYearEnd');
        Route::post('/processes/{process:processID}/reverseOrdinaryLevelEnd', [ProcessController::class, 'reverseOrdinaryLevelEnd'])->name('reverseOrdinaryLevelEnd');
        Route::post('/processes/{process:processID}/reverseAdvancedLevelEnd', [ProcessController::class, 'reverseAdvancedLevelEnd'])->name('reverseAdvancedLevelEnd');
        Route::patch('/processes/{process:processID}', [ProcessController::class, 'update'])->name('update');
        Route::delete('/processes/{process:processID}/trashed', [ProcessController::class, 'destroyTrashed'])->name('destroy.trashed');
    });
