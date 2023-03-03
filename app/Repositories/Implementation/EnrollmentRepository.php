<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Student;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllStudentsEnrollments()
    {
        return Student::query()->has('classes')->with(['classes', 'person'])->get();
    }

    /**
     * @return mixed
     */
    public function getStudentsNotInFreeCard()
    {
        $freeCard = Enrollment::query()
            ->where('paymentStatus', -1)
            ->distinct()
            ->pluck('studentID');

        $notFreeCard = Enrollment::query()
            ->whereNot('paymentStatus', -1)
            ->whereNotIn('studentID', $freeCard)
            ->distinct()
            ->pluck('studentID');

        return Student::query()->with('person')->findMany($notFreeCard);
    }

    /**
     * @return mixed
     */
    public function getStudentsInFreeCard()
    {
        $enrollment = Enrollment::query()
            ->where('paymentStatus', -1)
            ->distinct()
            ->pluck('studentID');

        return Student::query()->with('person')->findMany($enrollment);
    }

    /**
     * @param StoreEnrollmentRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function attachStudentEnrollments(StoreEnrollmentRequest $request)
    {
        $student = Student::query()->find(data_get($request, 'studentID'));

        $classes = Classes::query()->findMany(data_get($request, 'classID'));

        return DB::transaction(function () use ($request, $student, $classes) {

            foreach ($classes as $class) {
                $student->classes()->attach($class, [
                    'paymentStatus' => $class->feeType != 'Monthly' ? 0 : 1,
                    'enrolledDate' => Carbon::now()->format('Y-m-d')
                ]);
            }

            return $student;
        });
    }

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentEnrollmentsById(Student $student)
    {
        return Student::query()->with(['classes', 'person'])->find($student);
    }

    /**
     * @param Student $student
     * @return mixed
     * @throws Exception
     */
    public function getClassesNotInStudent(Student $student)
    {
        $enrollment = Enrollment::query()->where('studentID', $student->studentID)->pluck('classID');

        return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
            ->where('status', 'Active')
            ->whereNotIn('classID', $enrollment)
            ->get();
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateStudentFreeClass(UpdateEnrollmentRequest $request, Student $student, Classes $class)
    {
        $student = Student::query()->find($student->studentID);

        $class = Classes::query()->find($class->classID);

        $student->classes()->updateExistingPivot($class, [
            'paymentStatus' => $request->paymentStatus == -1 ? -1 : ($class->feeType != "Daily" ? data_get($request, 'paymentStatus') : 0)
        ]);

        return $student;
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateStudentStatus(UpdateEnrollmentRequest $request, Student $student, Classes $class)
    {
        $student = Student::query()->find($student->studentID);

        $class = Classes::query()->find($class->classID);

        $student->classes()->updateExistingPivot($class, [
            'status' => data_get($request, 'status')
        ]);

        return $student;
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return mixed
     * @throws Throwable
     */
    public function updateStudentStatusForAll(UpdateEnrollmentRequest $request, Student $student)
    {
        $student = Student::query()->find($student->studentID);

        return DB::transaction(function () use ($request, $student) {

            $student->classes()->newPivotStatement()->where('enrollment.studentID', $student->studentID)
                ->update([
                    'status' => data_get($request, 'status')
                ]);

            return $student;
        });
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function updateDailyClassPaidStatus(UpdateEnrollmentRequest $request, Student $student, Classes $class)
    {
        $student->classes()
            ->where('enrollment.classID', $class->classID)
            ->where('enrollment.status', '1')
            ->whereNot('enrollment.paymentStatus', '0')
            ->decrement('paymentStatus', data_get($request, 'decrement', 1));

        return $student;
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return void
     * @throws Throwable
     */
    public function updateMonthlyClassPaidStatus(UpdateEnrollmentRequest $request, Student $student)
    {
        DB::transaction(function () use ($request, $student) {

            foreach ($request->classes as $class) {
                $student->classes()
                    ->where('enrollment.classID', data_get($class, 'classID'))
                    ->where('enrollment.status', '1')
                    ->whereNot('enrollment.paymentStatus', '0')
                    ->decrement('paymentStatus', data_get($class, 'decrement', 1));
            }
        });
    }

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     * @throws Exception
     */
    public function detachStudentEnrollments(Request $request, Student $student)
    {
        $student = Student::query()->find($student->studentID);

        $deleted = $student->classes()->detach(data_get($request, 'classID'));

        if (!$deleted){
            throw new Exception('Failed to detach Enrollment of: ' . $student->studentID);
        }

        return $deleted;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllClassesEnrollments()
    {
        return Classes::query()->has('students')->with(['students', 'students.person'])->get();
    }

    /**
     * @param StoreEnrollmentRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function attachClassEnrollments(StoreEnrollmentRequest $request)
    {
        $class = Classes::query()->find(data_get($request, 'classID'));

        return DB::transaction(function () use ($request, $class) {

            $class->students()->attach(data_get($request, 'studentID'), [
                'paymentStatus' => $class->feeType != 'Monthly' ? 0 : 1,
                'enrolledDate' => Carbon::now()->format('Y-m-d')
            ]);

            return $class;
        });
    }

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getClassEnrollmentsById(Classes $class)
    {
        return Classes::query()->with(['students', 'students.person'])->find($class);
    }

    /**
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function getStudentsNotInClass(Classes $class)
    {
        $enrollment = Enrollment::query()->where('classID', $class->classID)->pluck('studentID');

        return Student::query()->with('person')
            ->join('people', 'students.studentID', 'people.personID')
            ->where('people.status', 'Active')
            ->whereNotIn('studentID', $enrollment)
            ->get();
    }

    /**
     * @param Classes $class
     * @param Student $student
     * @return mixed
     */
    public function updateDailyClassPaymentStatus(Classes $class, Student $student)
    {
        $class->students()
            ->where('enrollment.studentID', $student->studentID)
            ->where('enrollment.status', '1')
            ->whereNot('enrollment.paymentStatus', '-1')
            ->increment('paymentStatus');

        return $class;
    }

    /**
     * @param Classes $class
     * @param Student $student
     * @return mixed
     */
    public function updateDailyClassPaymentStatusDecrement(Classes $class, Student $student)
    {
        $class->students()
            ->where('enrollment.studentID', $student->studentID)
            ->where('enrollment.status', '1')
            ->whereNotIn('enrollment.paymentStatus', ['-1', '0'])
            ->decrement('paymentStatus');

        return $class;
    }

    /**
     * @param UpdateEnrollmentRequest $request
     * @param Classes $class
     * @return mixed
     * @throws Throwable
     */
    public function updateClassStatusForAll(UpdateEnrollmentRequest $request, Classes $class)
    {
        $class = Classes::query()->find($class->classID);

        return DB::transaction(function () use ($request, $class) {

            $class->students()->newPivotStatement()->where('enrollment.classID', $class->classID)
                ->update([
                    'status' => data_get($request, 'status')
                ]);

            return $class;
        });
    }

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function detachClassEnrollments(Request $request, Classes $class)
    {
        $class = Classes::query()->find($class->classID);

        $deleted = $class->students()->detach(data_get($request, 'studentID'));

        if (!$deleted){
            throw new Exception('Failed to detach Enrollment of: ' . $class->classID);
        }

        return $deleted;
    }
}
