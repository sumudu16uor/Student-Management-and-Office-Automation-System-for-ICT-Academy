<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Student;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllAttendances(Request $request)
    {
        return Classes::query()
            ->with(['attendances' => function ($query) use ($request) {
                $query->where('date', data_get($request, 'date'));
            }])->withCount([
                'attendances as present_count' => function (Builder $query) use ($request) {
                    $query->where('attendances.attendStatus', 1)
                        ->where('attendances.date', data_get($request, 'date'));
                },
                'attendances as absent_count' => function (Builder $query) use ($request) {
                    $query->where('attendances.attendStatus', 0)
                        ->where('attendances.date', data_get($request, 'date'));
                }
            ])
            ->whereHas('attendances', function (Builder $query) use ($request) {
                $query->where('date', data_get($request, 'date'));
            })->get();
    }

    /**
     * @param StoreAttendanceRequest $request
     * @return mixed
     */
    public function addStudentToAttendance(StoreAttendanceRequest $request)
    {
        return Attendance::query()->create([
            'studentID' => data_get($request, 'studentID'),
            'classID' => data_get($request, 'classID'),
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function getStudentAttendanceById(Request $request, Student $student)
    {
        return Attendance::query()->with(['class', 'student.person'])
            ->join('enrollment', function ($join) {
                $join->on('attendances.studentID', 'enrollment.studentID')
                    ->on('attendances.classID', 'enrollment.classID')
                    ->where('enrollment.status', 1);
            })
            ->where('attendances.studentID', $student->studentID)
            ->where('attendances.classID', data_get($request, 'classID'))
            ->where('attendances.date', data_get($request, 'date'))
            ->get();
    }

    /**
     * @param UpdateAttendanceRequest $request
     * @param Student $student
     * @return mixed
     * @throws Exception
     */
    public function updateMarkStudentAttendance(UpdateAttendanceRequest $request, Student $student)
    {
        $updated = $student->attendances()
            ->where('attendances.classID', data_get($request, 'classID'))
            ->where('attendances.date', data_get($request, 'date'))
            ->update([
                'time' => data_get($request, 'attendStatus') != 0 ? Carbon::now()->format('h:i:s') : null,
                'attendStatus' => data_get($request, 'attendStatus'),
            ]);

        if (!$updated) {
            throw new Exception('Failed to update student\'s attendance: '
                . $student->studentID . ', ' . $request->classID . ', ' .  $request->date);
        }

        return $updated;
    }

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     * @throws Exception
     */
    public function removeStudentAttendance(Request $request, Student $student)
    {
        $deleted = $student->attendances()
            ->where('attendances.classID', data_get($request, 'classID'))
            ->where('attendances.date', data_get($request, 'date'))
            ->delete();

        if (!$deleted){
            throw new Exception('Failed to delete student\'s attendance: '
                . $student->studentID . ', ' . $request->classID . ', ' .  $request->date);
        }

        return $deleted;
    }

    /**
     * @param StoreAttendanceRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function addClassToAttendance(StoreAttendanceRequest $request)
    {
        $class = Classes::query()->find(data_get($request, 'classID'));

        $students = Enrollment::query()
            ->where('classID', $class->classID)
            ->where('status', '1')
            ->pluck("studentID");

        $attendance = array();

        foreach ($students as $student) {
            $attendance[] = [
                'studentID' => $student,
                'date' => Carbon::now()->format('Y-m-d')
            ];
        }

        return DB::transaction(function () use ($class, $attendance) {
            return $class->attendances()->createMany($attendance);
        });
    }

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function getClassAttendanceById(Request $request, Classes $class)
    {
        return Classes::query()
            ->with(['attendances' => function ($query) use ($request) {
                $query->join('enrollment', function ($join) {
                    $join->on('attendances.studentID', 'enrollment.studentID')
                        ->on('attendances.classID', 'enrollment.classID')
                        ->where('enrollment.status', 1);
                })->where('date', data_get($request, 'date'));
            }])->withCount([
                'attendances as present_count' => function (Builder $query) use ($request) {
                    $query->where('attendances.attendStatus', 1)
                        ->where('attendances.date', data_get($request, 'date'));
                },
                'attendances as absent_count' => function (Builder $query) use ($request) {
                    $query->where('attendances.attendStatus', 0)
                        ->where('attendances.date', data_get($request, 'date'));
                }
            ])
            ->whereHas('attendances', function (Builder $query) use ($request, $class) {
                $query->where('classID', $class->classID)
                    ->where('date', data_get($request, 'date'));
            })->get();
    }

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     */
    public function getClassAttendCount(Request $request, Classes $class)
    {
        return Attendance::query()
            ->where('classID', $class->classID)
            ->where('date', data_get($request, 'date'))
            ->get();
    }

    /**
     * @param UpdateAttendanceRequest $request
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function updateMarkClassAttendance(UpdateAttendanceRequest $request, Classes $class)
    {
        $updated = $class->attendances()
            ->where('attendances.date', data_get($request, 'date'))
            ->update([
                'time' => data_get($request, 'attendStatus') != 0 ? Carbon::now()->format('h:i:s') : null,
                'attendStatus' => data_get($request, 'attendStatus'),
            ]);

        if (!$updated) {
            throw new Exception('Failed to update class\'s attendance: ' . $request->classID . ', ' .  $request->date);
        }

        return $updated;
    }

    /**
     * @param Request $request
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function removeClassAttendance(Request $request, Classes $class)
    {
        $deleted = $class->attendances()
            ->where('attendances.date', data_get($request, 'date'))
            ->delete();

        if (!$deleted){
            throw new Exception('Failed to delete class\'s attendance: '
                . $class->classID . ', ' .  $request->date);
        }

        return $deleted;
    }
}
