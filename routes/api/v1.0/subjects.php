<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('SubjectManagement')
    ->as('subjects.')
    ->group(function (){
        Route::get('/subjects', [SubjectController::class, 'index'])->name('index');
        Route::get('/subjects/{subject:subjectID}', [SubjectController::class, 'show'])->name('show');
        Route::get('/subjects/{subject:subjectID}/classes', [SubjectController::class, 'showClasses'])->name('show.classes');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('store');
        Route::patch('/subjects/{subject:subjectID}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/subjects/{subject:subjectID}', [SubjectController::class, 'destroy'])->name('destroy');
    });
