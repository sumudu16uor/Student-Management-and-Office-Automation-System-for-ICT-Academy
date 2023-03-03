<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;

interface MarkRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllStudentsMarks(Request $request);

    /**
     * @param StoreMarkRequest $request
     * @return mixed
     */
    public function attachStudentMark(StoreMarkRequest $request);

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentMarkById(Student $student);

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function getStudentMarkByYear(Request $request, Student $student);

    /**
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkByStudentId(Student $student, Exam $exam);

    /**
     * @param UpdateMarkRequest $request
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     */
    public function updateStudentMark(UpdateMarkRequest $request, Student $student, Exam $exam);

    /**
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     */
    public function detachStudentMark(Student $student, Exam $exam);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExamsMarks(Request $request);

    /**
     * @param StoreMarkRequest $request
     * @return mixed
     */
    public function attachExamMarks(StoreMarkRequest $request);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkById(Exam $exam);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkAbsent(Exam $exam);

    /**
     * @param Request $request
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarksAbove(Request $request, Exam $exam);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkAttendCount(Exam $exam);

    /**
     * @param UpdateMarkRequest $request
     * @param Exam $exam
     * @return mixed
     */
    public function updateExamMarks(UpdateMarkRequest $request, Exam $exam);

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function detachExamMark(Exam $exam);
}
