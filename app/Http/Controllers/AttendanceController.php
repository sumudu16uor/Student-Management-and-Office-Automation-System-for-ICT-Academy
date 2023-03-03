<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceClassCollection;
use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\AttendanceResource;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendedStudentResource;
use App\Models\Classes;
use App\Models\Student;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * @var AttendanceRepositoryInterface
     */
    private AttendanceRepositoryInterface $attendanceRepository;

    /**
     * @param AttendanceRepositoryInterface $attendanceRepository
     */
    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AttendanceClassCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $attendance = $this->attendanceRepository->getAllAttendances($request);

        return new AttendanceClassCollection($attendance);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttendanceRequest $request
     * @return AttendanceResource
     */
    public function storeByStudent(StoreAttendanceRequest $request)
    {
        $created = $this->attendanceRepository->addStudentToAttendance($request);

        return new AttendanceResource($created);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttendanceRequest $request
     * @return JsonResponse
     */
    public function storeByClass(StoreAttendanceRequest $request)
    {
        $created = $this->attendanceRepository->addClassToAttendance($request);

        return new JsonResponse([
            'success' => $created->count() > 0,
            'status' => 'added',
            'attendance_count' => $created->count()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Student $student
     * @return AttendanceCollection
     */
    public function showByStudent(Request $request, Student $student)
    {
        $request->validate([
            'classID' => ['required', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'date' => ['required', 'date']
        ]);

        $attendance = $this->attendanceRepository->getStudentAttendanceById($request, $student);

        return new AttendanceCollection($attendance);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Classes $class
     * @return AttendanceClassCollection
     */
    public function showByClass(Request $request, Classes $class)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $attendance = $this->attendanceRepository->getClassAttendanceById($request, $class);

        return new AttendanceClassCollection($attendance);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Classes $class
     * @return JsonResponse
     */
    public function showAttendByClass(Request $request, Classes $class)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $classes = $this->attendanceRepository->getClassAttendanceById($request, $class);

        $present = array();
        $absent = array();

        foreach ($classes as $class) {
            foreach ($class->attendances as $student) {
                if ($student->attendStatus == 1) {
                    $present[] = $student;
                } else {
                    $absent[] = $student;
                }
            }
        }

        return new JsonResponse([
            'classID' => $class->classID,
            'className' => $class->className,
            'present' => AttendedStudentResource::collection($present),
            'absent' => AttendedStudentResource::collection($absent),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Classes $class
     * @return JsonResponse
     */
    public function showAttendCount(Request $request, Classes $class)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $attendances = $this->attendanceRepository->getClassAttendCount($request, $class);

        $present = 0;
        $absent = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->attendStatus == 1) {
                ++$present;
            } else {
                ++$absent;
            }
        }

        return new JsonResponse([
            'success' => true,
            'classID' => $class->classID,
            'present_count' => $present,
            'absent_count' => $absent,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAttendanceRequest $request
     * @param Student $student
     * @return JsonResponse
     */
    public function updateByStudent(UpdateAttendanceRequest $request, Student $student)
    {
        $updated = $this->attendanceRepository->updateMarkStudentAttendance($request, $student);

        return new JsonResponse([
            'success' => $updated > 0,
            'status' => 'updated',
            'attendStatus' => data_get($request, 'attendStatus'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAttendanceRequest $request
     * @param Classes $class
     * @return JsonResponse
     */
    public function updateByClass(UpdateAttendanceRequest $request, Classes $class)
    {
        $updated = $this->attendanceRepository->updateMarkClassAttendance($request, $class);

        return new JsonResponse([
            'success' => $updated > 0,
            'status' => 'updated',
            'attendStatus' => data_get($request, 'attendStatus'),
            'attendance_count' => $updated
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Student $student
     * @return JsonResponse
     */
    public function destroyByStudent(Request $request, Student $student)
    {
        $request->validate([
            'classID' => ['required', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'date' => ['required', 'date']
        ]);

        $deleted = $this->attendanceRepository->removeStudentAttendance($request, $student);

        return new JsonResponse([
            'success' => $deleted > 0,
            'status' => 'deleted',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Classes $class
     * @return JsonResponse
     */
    public function destroyByClass(Request $request, Classes $class)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $deleted = $this->attendanceRepository->removeClassAttendance($request, $class);

        return new JsonResponse([
            'success' => $deleted > 0,
            'status' => 'deleted',
            'attendance_count' => $deleted
        ]);
    }
}
