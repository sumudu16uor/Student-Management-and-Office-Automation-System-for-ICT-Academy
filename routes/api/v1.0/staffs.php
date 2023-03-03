<?php

use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('StaffManagement')
    ->as('staffs.')
    ->group(function (){
        Route::get('/staffs', [StaffController::class, 'index'])->name('index');
        Route::get('/staffs/{staff:staffID}', [StaffController::class, 'show'])->name('show');
        Route::post('/staffs', [StaffController::class, 'store'])->name('store');
        Route::patch('/staffs/{staff:staffID}', [StaffController::class, 'update'])->name('update');
        Route::delete('/staffs/{staff:staffID}', [StaffController::class, 'destroy'])->name('destroy');
    });
