<?php

use App\Http\Controllers\ParentsController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('ParentManagement')
    ->as('parents.')
    ->group(function (){
        Route::get('/parents', [ParentsController::class, 'index'])->name('index');
        Route::get('/parents/{parent:studentID}', [ParentsController::class, 'show'])->name('show');
        Route::post('/parents', [ParentsController::class, 'store'])->name('store');
        Route::patch('/parents/{parent:studentID}', [ParentsController::class, 'update'])->name('update');
        Route::delete('/parents/{parent:studentID}', [ParentsController::class, 'destroy'])->name('destroy');
    });
