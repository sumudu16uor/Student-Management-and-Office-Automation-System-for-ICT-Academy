<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('ExamManagement')
    ->as('exams.')
    ->group(function (){
        Route::get('/exams', [ExamController::class, 'index'])->name('index');
        Route::get('/exams/month', [ExamController::class, 'indexByMonth'])->name('index.month');
        Route::get('/exams/{exam:examID}', [ExamController::class, 'show'])->name('show');
        Route::post('/exams', [ExamController::class, 'store'])->name('store');
        Route::patch('/exams/{exam:examID}', [ExamController::class, 'update'])->name('update');
        Route::delete('/exams/{exam:examID}', [ExamController::class, 'destroy'])->name('destroy');
    });
