<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

interface StudentRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllStudents(Request $request);

    /**
     * @param StoreStudentRequest $request
     * @return mixed
     */
    public function createStudent(StoreStudentRequest $request);

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentById(Student $student);

    /**
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @return mixed
     */
    public function updateStudent(UpdateStudentRequest $request, Student $student);

    /**
     * @param Student $student
     * @return mixed
     */
    public function forceDeleteStudent(Student $student);
}
