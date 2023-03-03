<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;

interface EnrollmentRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllStudentsEnrollments();

    /**
     * @return mixed
     */
    public function getStudentsNotInFreeCard();

    /**
     * @return mixed
     */
    public function getStudentsInFreeCard();

    /**
     * @param StoreEnrollmentRequest $request
     * @return mixed
     */
    public function attachStudentEnrollments(StoreEnrollmentRequest $request);

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentEnrollmentsById(Student $student);

    /**
     * @param Student $student
     * @return mixed
     */
    public function getClassesNotInStudent(Student $student);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateStudentFreeClass(UpdateEnrollmentRequest $request, Student $student, Classes $class);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateStudentStatus(UpdateEnrollmentRequest $request, Student $student, Classes $class);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return mixed
     */
    public function updateStudentStatusForAll(UpdateEnrollmentRequest $request, Student $student);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateDailyClassPaidStatus(UpdateEnrollmentRequest $request, Student $student, Classes $class);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return void
     */
    public function updateMonthlyClassPaidStatus(UpdateEnrollmentRequest $request, Student $student);

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function detachStudentEnrollments(Request $request, Student $student);

    /**
     * @return mixed
     */
    public function getAllClassesEnrollments();

    /**
     * @param StoreEnrollmentRequest $request
     * @return mixed
     */
    public function attachClassEnrollments(StoreEnrollmentRequest $request);

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getClassEnrollmentsById(Classes $class);

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getStudentsNotInClass(Classes $class);

    /**
     * @param Classes $class
     * @param Student $student
     * @return mixed
     */
    public function updateDailyClassPaymentStatus(Classes $class, Student $student);

    /**
     * @param Classes $class
     * @param Student $student
     * @return mixed
     */
    public function updateDailyClassPaymentStatusDecrement(Classes $class, Student $student);

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Classes $class
     * @return mixed
     */
    public function updateClassStatusForAll(UpdateEnrollmentRequest $request, Classes $class);

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function detachClassEnrollments(Request $request, Classes $class);
}
