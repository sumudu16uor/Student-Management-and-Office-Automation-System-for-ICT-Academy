<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('StudentManagement')
    ->as('students.')
    ->group(function (){
        Route::get('/students', [StudentController::class, 'index'])->name('index');
        Route::get('/students/{student:studentID}', [StudentController::class, 'show'])->name('show');
        Route::post('/students', [StudentController::class, 'store'])->name('store');
        Route::patch('/students/{student:studentID}', [StudentController::class, 'update'])->name('update');
        Route::delete('/students/{student:studentID}', [StudentController::class, 'destroy'])->name('destroy');
    });
