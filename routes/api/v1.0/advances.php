<?php

use App\Http\Controllers\AdvanceController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('AdvanceManagement')
    ->as('advances.')
    ->group(function (){
        Route::get('/advances', [AdvanceController::class, 'index'])->name('index');
        Route::get('/advances/trashed', [AdvanceController::class, 'indexTrashed'])->name('index.trashed');
        Route::get('/advances/{advance:advanceID}', [AdvanceController::class, 'show'])->name('show');
        Route::post('/advances', [AdvanceController::class, 'store'])->name('store');
        Route::post('/advances/{advance:advanceID}/restore', [AdvanceController::class, 'restore'])->name('restore');
        Route::patch('/advances/{advance:advanceID}', [AdvanceController::class, 'update'])->name('update');
        Route::delete('/advances/{advance:advanceID}', [AdvanceController::class, 'destroy'])->name('destroy');
        Route::delete('/advances/{advance:advanceID}/trashed', [AdvanceController::class, 'destroyTrashed'])->name('destroy.trashed');
    });
