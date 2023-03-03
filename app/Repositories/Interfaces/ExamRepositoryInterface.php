<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use Illuminate\Http\Request;

interface ExamRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExams(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExamsByMonth(Request $request);

    /**
     * @param StoreExamRequest $request
     * @return mixed
     */
    public function createExam(StoreExamRequest $request);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamById(Exam $exam);

    /**
     * @param UpdateExamRequest $request
     * @param Exam $exam
     * @return mixed
     */
    public function updateExam(UpdateExamRequest $request, Exam $exam);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function forceDeleteExam(Exam $exam);
}
