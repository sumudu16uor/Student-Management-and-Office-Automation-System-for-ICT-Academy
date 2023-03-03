<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;

interface AttendanceRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllAttendances(Request $request);

    /**
     * @param StoreAttendanceRequest $request
     * @return mixed
     */
    public function addStudentToAttendance(StoreAttendanceRequest $request);

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function getStudentAttendanceById(Request $request, Student $student);

    /**
     * @param UpdateAttendanceRequest $request
     * @param Student $student
     * @return mixed
     */
    public function updateMarkStudentAttendance(UpdateAttendanceRequest $request, Student $student);

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function removeStudentAttendance(Request $request, Student $student);

    /**
     * @param StoreAttendanceRequest $request
     * @return mixed
     */
    public function addClassToAttendance(StoreAttendanceRequest $request);

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function getClassAttendanceById(Request $request, Classes $class);

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function getClassAttendCount(Request $request, Classes $class);

    /**
     * @param UpdateAttendanceRequest $request
     * @param Classes $class
     * @return mixed
     */
    public function updateMarkClassAttendance(UpdateAttendanceRequest $request, Classes $class);

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function removeClassAttendance(Request $request, Classes $class);
}
