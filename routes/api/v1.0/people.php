<?php

use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('PersonManagement')
    ->as('people.')
    ->group(function (){
        Route::get('/people', [PersonController::class, 'index'])->name('index');
        Route::get('/people/{person:personID}', [PersonController::class, 'show'])->name('show');
        Route::post('/people', [PersonController::class, 'store'])->name('store');
        Route::patch('/people/{person:personID}', [PersonController::class, 'update'])->name('update');
        Route::delete('/people/{person:personID}', [PersonController::class, 'destroy'])->name('destroy');
    });
