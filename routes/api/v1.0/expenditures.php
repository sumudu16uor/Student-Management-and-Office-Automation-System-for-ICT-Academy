<?php

use App\Http\Controllers\ExpenditureController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('ExpenditureManagement')
    ->as('expenditures.')
    ->group(function (){
        Route::get('/expenditures', [ExpenditureController::class, 'index'])->name('index');
        Route::get('/expenditures/trashed', [ExpenditureController::class, 'indexTrashed'])->name('index.trashed');
        Route::get('/expenditures/{expenditure:expenseID}', [ExpenditureController::class, 'show'])->name('show');
        Route::post('/expenditures', [ExpenditureController::class, 'store'])->name('store');
        Route::post('/expenditures/{expenditure:expenseID}/restore', [ExpenditureController::class, 'restore'])->name('restore');
        Route::patch('/expenditures/{expenditure:expenseID}', [ExpenditureController::class, 'update'])->name('update');
        Route::delete('/expenditures/{expenditure:expenseID}', [ExpenditureController::class, 'destroy'])->name('destroy');
        Route::delete('/expenditures/{expenditure:expenseID}/trashed', [ExpenditureController::class, 'destroyTrashed'])->name('destroy.trashed');
    });
