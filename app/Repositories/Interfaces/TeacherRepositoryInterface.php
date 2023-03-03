<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

interface TeacherRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTeachers(Request $request);

    /**
     * @param StoreTeacherRequest $request
     * @return mixed
     */
    public function createTeacher(StoreTeacherRequest $request);

    /**
     * @param Teacher $teacher
     * @return mixed
     */
    public function getTeacherById(Teacher $teacher);

    /**
     * @param Teacher $teacher
     * @return mixed
     */
    public function getClassesWithExamsByTeacherId(Teacher $teacher);

    /**
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return mixed
     */
    public function updateTeacher(UpdateTeacherRequest $request, Teacher $teacher);

    /**
     * @param Teacher $teacher
     * @return mixed
     */
    public function forceDeleteTeacher(Teacher $teacher);
}
