<?php

use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('ClassManagement')
    ->as('classes.')
    ->group(function (){
        Route::get('/classes', [ClassesController::class, 'index'])->name('index');
        Route::get('/classes/{class:classID}', [ClassesController::class, 'show'])->name('show');
        Route::get('/classes/{class:classID}/exams', [ClassesController::class, 'showExams'])->name('show.exams');
        Route::post('/classes', [ClassesController::class, 'store'])->name('store');
        Route::patch('/classes/{class:classID}', [ClassesController::class, 'update'])->name('update');
        Route::delete('/classes/{class:classID}', [ClassesController::class, 'destroy'])->name('destroy');
    });
