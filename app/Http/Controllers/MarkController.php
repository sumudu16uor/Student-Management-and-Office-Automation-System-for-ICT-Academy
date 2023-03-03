<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarkExamCollection;
use App\Http\Resources\MarkExamResource;
use App\Http\Resources\MarkStudentCollection;
use App\Http\Resources\MarkStudentResource;
use App\Models\Exam;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Student;
use App\Repositories\Interfaces\MarkRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    /**
     * @var MarkRepositoryInterface
     */
    private MarkRepositoryInterface $markRepository;

    /**
     * @param MarkRepositoryInterface $markRepository
     */
    public function __construct(MarkRepositoryInterface $markRepository)
    {
        $this->markRepository = $markRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return MarkStudentCollection
     */
    public function indexByStudents(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y']
        ]);

        $marks = $this->markRepository->getAllStudentsMarks($request);

        return new MarkStudentCollection($marks);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return MarkExamCollection
     */
    public function indexByExams(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y']
        ]);

        $marks = $this->markRepository->getAllExamsMarks($request);

        return new MarkExamCollection($marks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMarkRequest $request
     * @return JsonResponse
     */
    public function storeByStudent(StoreMarkRequest $request)
    {
        $created = $this->markRepository->attachStudentMark($request);

        return new JsonResponse([
            'success' => true,
            'status' => 'attached',
            'studentID' => $created->studentID,
            'attached_count' => count((array)$request->examID),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMarkRequest $request
     * @return JsonResponse
     */
    public function storeByExam(StoreMarkRequest $request)
    {
        $created = $this->markRepository->attachExamMarks($request);

        return new JsonResponse([
            'success' => true,
            'status' => 'attached',
            'examID' => $request->examID,
            'attached_count' => count((array)$created),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return MarkStudentResource
     */
    public function showByStudent(Student $student)
    {
        $marks = $this->markRepository->getStudentMarkById($student);

        return new MarkStudentResource($marks);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Student $student
     * @return MarkStudentResource
     */
    public function showByYear(Request $request, Student $student)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y']
        ]);

        $marks = $this->markRepository->getStudentMarkByYear($request, $student);

        return new MarkStudentResource($marks);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @param Exam $exam
     * @return MarkStudentResource
     */
    public function showExamByStudent(Student $student, Exam $exam)
    {
        $mark = $this->markRepository->getExamMarkByStudentId($student, $exam);

        return new MarkStudentResource($mark);
    }

    /**
     * Display the specified resource.
     *
     * @param Exam $exam
     * @return MarkExamResource
     */
    public function showByExam(Exam $exam)
    {
        $marks = $this->markRepository->getExamMarkById($exam);

        return new MarkExamResource($marks);
    }

    /**
     * Display the specified resource.
     *
     * @param Exam $exam
     * @return MarkExamResource
     */
    public function showAbsent(Exam $exam)
    {
        $marks = $this->markRepository->getExamMarkAbsent($exam);

        return new MarkExamResource($marks);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Exam $exam
     * @return MarkExamResource
     */
    public function showMarksAbove(Request $request, Exam $exam)
    {
        $request->validate([
            'mark' => ['nullable', 'integer']
        ]);

        $marks = $this->markRepository->getExamMarksAbove($request, $exam);

        return new MarkExamResource($marks);
    }

    /**
     * Display the specified resource.
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function showAttendCount(Exam $exam)
    {
        $present = 0;
        $absent = 0;

        $marks = $this->markRepository->getExamMarkAttendCount($exam);

        foreach ($marks as $mark) {
            if ($mark->mark == 'Ab') {
                ++$absent;
            } else {
                ++$present;
            }
        }

        return new JsonResponse([
            'success' => true,
            'examID' => $exam->examID,
            'present_count' => $present,
            'absent_count' => $absent,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMarkRequest $request
     * @param Student $student
     * @param Exam $exam
     * @return JsonResponse
     */
    public function updateByStudent(UpdateMarkRequest $request, Student $student, Exam $exam)
    {
        $updated = $this->markRepository->updateStudentMark($request, $student, $exam);

        $result = $student->exams()->find($exam)->result;

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'studentID' => $updated->studentID,
            'studentName' => $updated->person->firstName. ' ' . $updated->person->lastName,
            'exams' => [
                'examID' => $exam->examID,
                'exam' => $exam->exam,
                'mark'=> $result->mark != 'Ab' ? (int)$result->mark : $result->mark,
                'totalMark' => $exam->totalMark,
                'date' => $exam->date,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMarkRequest $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function updateByExam(UpdateMarkRequest $request, Exam $exam)
    {
        $updated = $this->markRepository->updateExamMarks($request, $exam);

        return new JsonResponse([
            'success' => true,
            'status' => 'updated',
            'examID' => $exam->examID,
            'exam' => $exam->exam,
            'mark'=> $request->mark != 'Ab' ? (int)$request->mark : $request->mark,
            'updated_count' => $updated,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @param Exam $exam
     * @return JsonResponse
     */
    public function destroyByStudent(Student $student, Exam $exam)
    {
        $deleted = $this->markRepository->detachStudentMark($student, $exam);

        return new JsonResponse([
            'success' => true,
            'status' => 'detached',
            'studentID' => $student->studentID,
            'examID' => $exam->examID,
            'detached_count' => $deleted,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function destroyByExam(Exam $exam)
    {
        $deleted = $this->markRepository->detachExamMark($exam);

        return new JsonResponse([
            'success' => true,
            'status' => 'detached',
            'examID' => $exam->examID,
            'exam' => $exam->exam,
            'detached_count' => $deleted,
        ]);
    }
}
