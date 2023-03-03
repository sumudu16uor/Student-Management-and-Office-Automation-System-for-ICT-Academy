<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('CategoryManagement')
    ->as('categories.')
    ->group(function (){
        Route::get('/categories', [CategoryController::class, 'index'])->name('index');
        Route::get('/categories/{category:categoryID}', [CategoryController::class, 'show'])->name('show');
        Route::get('/categories/{category:categoryID}/subjects', [CategoryController::class, 'showSubjects'])->name('show.subjects');
        Route::post('/categories', [CategoryController::class, 'store'])->name('store');
        Route::patch('/categories/{category:categoryID}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/categories/{category:categoryID}', [CategoryController::class, 'destroy'])->name('destroy');
    });
