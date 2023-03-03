<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('EnrollmentManagement')
    ->as('enrollments.')
    ->group(function (){
        Route::get('/students', [EnrollmentController::class, 'indexByStudents'])->name('index.students');
        Route::get('/students/notInFreeCard', [EnrollmentController::class, 'indexNotInFreeCard'])->name('index.notInFreeCard');
        Route::get('/students/inFreeCard', [EnrollmentController::class, 'indexInFreeCard'])->name('index.inFreeCard');
        Route::get('/students/{student:studentID}', [EnrollmentController::class, 'showByStudent'])->name('show.student');
        Route::get('/students/{student:studentID}/notInStudent', [EnrollmentController::class, 'showNotInStudent'])->name('show.notInStudent');
        Route::post('/students', [EnrollmentController::class, 'storeByStudent'])->name('store.student');
        Route::patch('/students/{student:studentID}/classes/{class:classID}/freeClass', [EnrollmentController::class, 'updateToFreeCard'])->name('update.toFreeCard');
        Route::patch('/students/{student:studentID}/classes/{class:classID}', [EnrollmentController::class, 'updateStudentStatus'])->name('update.student.status');
        Route::patch('/students/{student:studentID}/classes', [EnrollmentController::class, 'updateStudentStatusForAll'])->name('update.student.statusForAll');
        Route::patch('/students/{student:studentID}/classes/{class:classID}/daily', [EnrollmentController::class, 'updateDailyPaid'])->name('update.dailyPaid');
        Route::patch('/students/{student:studentID}/monthly', [EnrollmentController::class, 'updateMonthlyPaid'])->name('update.monthlyPaid');
        Route::delete('/students/{student:studentID}', [EnrollmentController::class, 'destroyByStudent'])->name('destroy.student');

        Route::get('/classes', [EnrollmentController::class, 'indexByClasses'])->name('index.classes');
        Route::get('/classes/{class:classID}', [EnrollmentController::class, 'showByClass'])->name('show.class');
        Route::get('/classes/{class:classID}/notInClass', [EnrollmentController::class, 'showNotInClass'])->name('show.notInClass');
        Route::post('/classes', [EnrollmentController::class, 'storeByClass'])->name('store.class');
        Route::patch('/classes/{class:classID}/students/{student:studentID}/daily', [EnrollmentController::class, 'updateDaily'])->name('update.daily');
        Route::patch('/classes/{class:classID}/students/{student:studentID}/dailyDecrement', [EnrollmentController::class, 'updateDailyDecrement'])->name('update.daily.decrement');
        Route::patch('/classes/{class:classID}/students', [EnrollmentController::class, 'updateClassStatusForAll'])->name('update.class.statusForAll');
        Route::delete('/classes/{class:classID}', [EnrollmentController::class, 'destroyByClass'])->name('destroy.class');
    });
