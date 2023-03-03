<?php

use App\Http\Controllers\FeeController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('FeeManagement')
    ->as('fees.')
    ->group(function (){
        Route::get('/fees', [FeeController::class, 'index'])->name('index');
        Route::get('/fees/trashed', [FeeController::class, 'indexTrashed'])->name('index.trashed');
        Route::get('/fees/todayCollection', [FeeController::class, 'indexTodayCollection'])->name('index.todayCollection');
        Route::get('/fees/{fee:feeID}', [FeeController::class, 'show'])->name('show');
        Route::post('/students/{student:studentID}/classes/{class:classID}/fees', [FeeController::class, 'storeOne'])->name('store.one');
        Route::post('/students/{student:studentID}/fees', [FeeController::class, 'storeMany'])->name('store.many');
        Route::post('/fees/{fee:feeID}/restore', [FeeController::class, 'restore'])->name('restore');
        Route::patch('/fees/{fee:feeID}', [FeeController::class, 'update'])->name('update');
        Route::delete('/fees/{fee:feeID}', [FeeController::class, 'destroy'])->name('destroy');
        Route::delete('/fees/{fee:feeID}/trashed', [FeeController::class, 'destroyTrashed'])->name('destroy.trashed');
    });
