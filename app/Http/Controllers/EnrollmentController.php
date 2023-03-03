<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnrollmentClassCollection;
use App\Http\Resources\EnrollmentClassResource;
use App\Http\Resources\EnrollmentStudentCollection;
use App\Http\Resources\EnrollmentStudentResource;
use App\Http\Resources\EnrollmentResource;
use App\Models\Classes;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Student;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    /**
     * @var EnrollmentRepositoryInterface
     */
    private EnrollmentRepositoryInterface $enrollmentRepository;

    /**
     * @param EnrollmentRepositoryInterface $enrollmentRepository
     */
    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return EnrollmentStudentCollection
     */
    public function indexByStudents()
    {
        $enrollments = $this->enrollmentRepository->getAllStudentsEnrollments();

        return new EnrollmentStudentCollection($enrollments);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function indexNotInFreeCard()
    {
        $students = $this->enrollmentRepository->getStudentsNotInFreeCard();

        return EnrollmentResource::collection($students);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function indexInFreeCard()
    {
        $students = $this->enrollmentRepository->getStudentsInFreeCard();

        return EnrollmentResource::collection($students);
    }

    /**
     * Display a listing of the resource.
     *
     * @return EnrollmentClassCollection
     */
    public function indexByClasses()
    {
        $enrollments = $this->enrollmentRepository->getAllClassesEnrollments();

        return new EnrollmentClassCollection($enrollments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEnrollmentRequest $request
     * @return JsonResponse
     */
    public function storeByStudent(StoreEnrollmentRequest $request)
    {
        $created = $this->enrollmentRepository->attachStudentEnrollments($request);

        return new JsonResponse([
            'success' => true,
            'status' => 'attached',
            'studentID' => $created->studentID,
            'attached_count' => count((array)$request->classID),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEnrollmentRequest $request
     * @return JsonResponse
     */
    public function storeByClass(StoreEnrollmentRequest $request)
    {
        $created = $this->enrollmentRepository->attachClassEnrollments($request);

        return new JsonResponse([
            'success' => true,
            'status' => 'attached',
            'classID' => $created->classID,
            'attached_count' => count((array)$request->studentID),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return EnrollmentStudentCollection
     */
    public function showByStudent(Student $student)
    {
        $enrollment = $this->enrollmentRepository->getStudentEnrollmentsById($student);

        return new EnrollmentStudentCollection($enrollment);
    }

    /**
     * Display the specified resource.
     *
     * @param Classes $class
     * @return EnrollmentClassCollection
     */
    public function showByClass(Classes $class)
    {
        $enrollment = $this->enrollmentRepository->getClassEnrollmentsById($class);

        return new EnrollmentClassCollection($enrollment);
    }

    /**
     * Display the specified resource.
     *
     * @param Classes $class
     * @return JsonResponse
     */
    public function showNotInClass(Classes $class)
    {
        $students = $this->enrollmentRepository->getStudentsNotInClass($class);

        return new JsonResponse([
            'classID' => $class->classID,
            'className' => $class->className,
            'students' => EnrollmentResource::collection($students),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function showNotInStudent(Student $student)
    {
        $classes = $this->enrollmentRepository->getClassesNotInStudent($student);

        return new JsonResponse([
            'studentID' => $student->studentID,
            'studentName' => $student->person->firstName. ' ' . $student->person->lastName,
            'classes' => EnrollmentResource::collection($classes),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return JsonResponse
     */
    public function updateToFreeCard(UpdateEnrollmentRequest $request, Student $student, Classes $class)
    {
        $updated = $this->enrollmentRepository->updateStudentFreeClass($request, $student, $class);

        $enrollment = $student->classes()->find($class)->enrollment;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'paymentType' => $enrollment->paymentStatus >= 0 ? 'paid' : 'free',
            'student' => [
                'studentID' => $updated->studentID,
                'studentName' => $updated->person->firstName. ' ' . $updated->person->lastName,
                'class' => [
                    'classID' => $class->classID,
                    'className' => $class->className,
                    'paymentStatus'=> $enrollment->paymentStatus,
                    'enrolledDate' => $enrollment->enrolledDate,
                    'status' => $enrollment->status,
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return JsonResponse
     */
    public function updateStudentStatus(UpdateEnrollmentRequest $request, Student $student, Classes $class)
    {
        $updated = $this->enrollmentRepository->updateStudentStatus($request, $student, $class);

        $enrollment = $student->classes()->find($class)->enrollment;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'statusType' => $enrollment->status == 1 ? 'active' : 'deactivate',
            'student' => [
                'studentID' => $updated->studentID,
                'studentName' => $updated->person->firstName. ' ' . $updated->person->lastName,
                'class' => [
                    'classID' => $class->classID,
                    'className' => $class->className,
                    'paymentStatus'=> $enrollment->paymentStatus,
                    'enrolledDate' => $enrollment->enrolledDate,
                    'status' => $enrollment->status,
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return EnrollmentStudentResource
     */
    public function updateStudentStatusForAll(UpdateEnrollmentRequest $request, Student $student)
    {
        $updated = $this->enrollmentRepository->updateStudentStatusForAll($request, $student);

        return new EnrollmentStudentResource($updated);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @param Classes $class
     * @return JsonResponse
     */
    public function updateDailyPaid(UpdateEnrollmentRequest $request,Student $student, Classes $class)
    {
        $updated = $this->enrollmentRepository->updateDailyClassPaidStatus($request, $student, $class);

        $enrollment = $student->classes()->find($class)->enrollment;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'student' => [
                'studentID' => $updated->studentID,
                'studentName' => $updated->person->firstName. ' ' . $updated->person->lastName,
                'class' => [
                    'classID' => $class->classID,
                    'className' => $class->className,
                    'paymentStatus'=> $enrollment->paymentStatus,
                    'enrolledDate' => $enrollment->enrolledDate,
                    'status' => $enrollment->status,
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Student $student
     * @return JsonResponse
     */
    public function updateMonthlyPaid(UpdateEnrollmentRequest $request, Student $student)
    {
        $this->enrollmentRepository->updateMonthlyClassPaidStatus($request, $student);

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'updated_count' => count((array)$request->classes),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Classes $class
     * @param Student $student
     * @return JsonResponse
     */
    public function updateDaily(Classes $class, Student $student)
    {
        $updated = $this->enrollmentRepository->updateDailyClassPaymentStatus($class, $student);

        $enrollment = $class->students()->find($student)->enrollment;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'class' => [
                'classID' => $updated->classID,
                'className' => $updated->className,
                'student' => [
                    'studentID' => $student->studentID,
                    'studentName' => $student->person->firstName. ' ' . $student->person->lastName,
                    'paymentStatus'=> $enrollment->paymentStatus,
                    'enrolledDate' => $enrollment->enrolledDate,
                    'status' => $enrollment->status,
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Classes $class
     * @param Student $student
     * @return JsonResponse
     */
    public function updateDailyDecrement(Classes $class, Student $student)
    {
        $updated = $this->enrollmentRepository->updateDailyClassPaymentStatusDecrement($class, $student);

        $enrollment = $class->students()->find($student)->enrollment;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'class' => [
                'classID' => $updated->classID,
                'className' => $updated->className,
                'student' => [
                    'studentID' => $student->studentID,
                    'studentName' => $student->person->firstName. ' ' . $student->person->lastName,
                    'paymentStatus'=> $enrollment->paymentStatus,
                    'enrolledDate' => $enrollment->enrolledDate,
                    'status' => $enrollment->status,
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Classes $class
     * @return EnrollmentClassResource
     */
    public function updateClassStatusForAll(UpdateEnrollmentRequest $request, Classes $class)
    {
        $updated = $this->enrollmentRepository->updateClassStatusForAll($request, $class);

        return new EnrollmentClassResource($updated);
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
            'classID' => ['required', Rule::exists('classes', 'classID')],
        ]);

        $deleted = $this->enrollmentRepository->detachStudentEnrollments($request, $student);

        return new JsonResponse([
            'success' => true,
            'status' => 'detached',
            'studentID' => $student->studentID,
            'detached_count' => $deleted,
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
            'studentID' => ['required', Rule::exists('students', 'studentID')],
        ]);

        $deleted = $this->enrollmentRepository->detachClassEnrollments($request, $class);

        return new JsonResponse([
            'success' => true,
            'status' => 'detached',
            'classID' => $class->classID,
            'detached_count' => $deleted,
        ]);
    }
}
