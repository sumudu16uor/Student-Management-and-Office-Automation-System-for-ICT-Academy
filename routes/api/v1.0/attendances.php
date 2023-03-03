<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('AttendanceManagement')
    ->as('attendances.')
    ->group(function (){
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('index');
        Route::get('/attendances/students/{student:studentID}', [AttendanceController::class, 'showByStudent'])->name('show.student');
        Route::post('/attendances/students', [AttendanceController::class, 'storeByStudent'])->name('store.student');
        Route::patch('/attendances/students/{student:studentID}', [AttendanceController::class, 'updateByStudent'])->name('update.student');
        Route::delete('/attendances/students/{student:studentID}', [AttendanceController::class, 'destroyByStudent'])->name('destroy.student');

        Route::get('/attendances/classes/{class:classID}', [AttendanceController::class, 'showByClass'])->name('show.class');
        Route::get('/attendances/classes/{class:classID}/attend', [AttendanceController::class, 'showAttendByClass'])->name('show.attend');
        Route::get('/attendances/classes/{class:classID}/attendCount', [AttendanceController::class, 'showAttendCount'])->name('show.attendCount');
        Route::post('/attendances/classes', [AttendanceController::class, 'storeByClass'])->name('store.class');
        Route::patch('/attendances/classes/{class:classID}', [AttendanceController::class, 'updateByClass'])->name('update.class');
        Route::delete('/attendances/classes/{class:classID}', [AttendanceController::class, 'destroyByClass'])->name('destroy.class');
    });
