<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamCollection;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Repositories\Interfaces\ExamRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * @var ExamRepositoryInterface
     */
    private ExamRepositoryInterface $examRepository;

    /**
     * @param ExamRepositoryInterface $examRepository
     */
    public function __construct(ExamRepositoryInterface $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ExamCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y']
        ]);

        $exams = $this->examRepository->getAllExams($request);

        return new ExamCollection($exams);
    }

    /**
     * Display a listing of the resource.
     *
     * @return ExamCollection
     */
    public function indexByMonth(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date']
        ]);

        $exams = $this->examRepository->getAllExamsByMonth($request);

        return new ExamCollection($exams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreExamRequest $request
     * @return ExamResource
     */
    public function store(StoreExamRequest $request)
    {
        $created = $this->examRepository->createExam($request);

        return new ExamResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Exam $exam
     * @return ExamCollection
     */
    public function show(Exam $exam)
    {
        $exam = $this->examRepository->getExamById($exam);

        return new ExamCollection($exam);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateExamRequest $request
     * @param Exam $exam
     * @return ExamResource
     */
    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $updated = $this->examRepository->updateExam($request, $exam);

        return new ExamResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function destroy(Exam $exam)
    {
        $deleted = $this->examRepository->forceDeleteExam($exam);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new ExamResource($exam),
        ]);
    }
}
