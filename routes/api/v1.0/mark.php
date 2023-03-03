<?php

use App\Http\Controllers\MarkController;
use Illuminate\Support\Facades\Route;

Route::middleware([

])
    ->prefix('MarkManagement')
    ->as('marks.')
    ->group(function (){
        Route::get('/students', [MarkController::class, 'indexByStudents'])->name('index.students');
        Route::get('/students/{student:studentID}', [MarkController::class, 'showByStudent'])->name('show.student');
        Route::get('/students/{student:studentID}/marksByYear', [MarkController::class, 'showByYear'])->name('show.byYear');
        Route::get('/students/{student:studentID}/exams/{exam:examID}', [MarkController::class, 'showExamByStudent'])->name('show.examByStudent');
        Route::post('/students', [MarkController::class, 'storeByStudent'])->name('store.student');
        Route::patch('/students/{student:studentID}/exams/{exam:examID}', [MarkController::class, 'updateByStudent'])->name('update.student');
        Route::delete('/students/{student:studentID}/exams/{exam:examID}', [MarkController::class, 'destroyByStudent'])->name('destroy.student');

        Route::get('/exams', [MarkController::class, 'indexByExams'])->name('index.exams');
        Route::get('/exams/{exam:examID}', [MarkController::class, 'showByExam'])->name('show.exam');
        Route::get('/exams/{exam:examID}/absent', [MarkController::class, 'showAbsent'])->name('show.absent');
        Route::get('/exams/{exam:examID}/marksAbove', [MarkController::class, 'showMarksAbove'])->name('show.marksAbove');
        Route::get('/exams/{exam:examID}/attendCount', [MarkController::class, 'showAttendCount'])->name('show.attendCount');
        Route::post('/exams', [MarkController::class, 'storeByExam'])->name('store.exam');
        Route::patch('/exams/{exam:examID}', [MarkController::class, 'updateByExam'])->name('update.exam');
        Route::delete('/exams/{exam:examID}', [MarkController::class, 'destroyByExam'])->name('destroy.exam');
    });
