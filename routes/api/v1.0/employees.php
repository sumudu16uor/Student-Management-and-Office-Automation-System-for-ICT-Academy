<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('EmployeeManagement')
    ->as('employees.')
    ->group(function (){
        Route::get('/employees', [EmployeeController::class, 'index'])->name('index');
        Route::get('/employees/{employee:employeeID}', [EmployeeController::class, 'show'])->name('show');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('store');
        Route::patch('/employees/{employee:employeeID}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/employees/{employee:employeeID}', [EmployeeController::class, 'destroy'])->name('destroy');
    });
