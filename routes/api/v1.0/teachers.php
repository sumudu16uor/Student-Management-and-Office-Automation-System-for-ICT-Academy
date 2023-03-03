<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('TeacherManagement')
    ->as('teachers.')
    ->group(function (){
        Route::get('/teachers', [TeacherController::class, 'index'])->name('index');
        Route::get('/teachers/{teacher:teacherID}', [TeacherController::class, 'show'])->name('show');
        Route::get('/teachers/{teacher:teacherID}/classes', [TeacherController::class, 'showClassesWithExams'])->name('show.classesWithExams');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('store');
        Route::patch('/teachers/{teacher:teacherID}', [TeacherController::class, 'update'])->name('update');
        Route::delete('/teachers/{teacher:teacherID}', [TeacherController::class, 'destroy'])->name('destroy');
    });
