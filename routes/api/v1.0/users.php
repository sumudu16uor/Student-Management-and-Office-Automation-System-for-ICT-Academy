<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
])
    ->prefix('UserManagement')
    ->as('users.')
    ->group(function (){
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::get('/users/loginRecords', [UserController::class, 'indexLoginRecords'])->name('index.loginRecords');
        Route::get('/users/staffNotUser', [UserController::class, 'indexStaffNotUser'])->name('index.staffNotUser');
        Route::get('/users/teachersNotUser', [UserController::class, 'indexTeachersNotUser'])->name('index.teachersNotUser');
        Route::get('/users/{user:userID}', [UserController::class, 'show'])->name('show');
        Route::post('/users', [UserController::class, 'store'])->name('store');
        Route::post('/users/login', [UserController::class, 'login'])->withoutMiddleware(['auth:sanctum'])->name('login');
        Route::post('/users/{user:userID}/logout', [UserController::class, 'logout'])->name('logout');
        Route::post('/users/{user:userID}/confirmPassword', [UserController::class, 'confirmPassword'])->name('confirmPassword');
        Route::post('/users/forgotPassword', [UserController::class, 'forgotPassword'])->withoutMiddleware(['auth:sanctum'])->name('update.forgotPassword');
        Route::patch('/users/{user:userID}', [UserController::class, 'update'])->name('update');
        Route::patch('/users/{user:userID}/username', [UserController::class, 'changeUsername'])->name('update.username');
        Route::patch('/users/{user:userID}/restPassword', [UserController::class, 'restPassword'])->name('update.restPassword');
        Route::delete('/users/{user:userID}', [UserController::class, 'destroy'])->name('destroy');
    });
