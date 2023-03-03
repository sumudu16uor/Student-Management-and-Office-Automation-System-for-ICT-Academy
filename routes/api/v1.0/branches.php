<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;

Route::middleware([

])
    ->prefix('BranchManagement')
    ->as('branches.')
    ->group(function (){
        Route::get('/branches', [BranchController::class, 'index'])->name('index');
        Route::get('/branches/{branch:branchID}', [BranchController::class, 'show'])->name('show');
        Route::post('/branches', [BranchController::class, 'store'])->name('store');
        Route::patch('/branches/{branch:branchID}', [BranchController::class, 'update'])->name('update');
        Route::delete('/branches/{branch:branchID}', [BranchController::class, 'destroy'])->name('destroy');
    });
