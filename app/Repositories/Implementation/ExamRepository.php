<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExamRepository implements ExamRepositoryInterface
{
    /**
     * @var IDGenerateServiceInterface
     */
    private IDGenerateServiceInterface $IDGenerateService;

    /**
     * @param IDGenerateServiceInterface $IDGenerateService
     */
    public function __construct(IDGenerateServiceInterface $IDGenerateService)
    {
        $this->IDGenerateService = $IDGenerateService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExams(Request $request)
    {
        if ($request->date != null) {
            return Exam::query()->with(['class', 'subject', 'category', 'branch'])
                ->whereYear('date', data_get($request, 'date'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Exam::query()->with(['class', 'subject', 'category', 'branch'])
            ->whereYear('date', Carbon::now()->year)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExamsByMonth(Request $request)
    {
        if ($request->date != null) {
            return Exam::query()->with(['class', 'subject', 'category', 'branch'])
                ->whereYear('date', data_get($request, 'date'))
                ->whereMonth('date', Carbon::make(data_get($request, 'date'))->format('m'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Exam::query()->with(['class', 'subject', 'category', 'branch'])
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * @param StoreExamRequest $request
     * @return mixed
     */
    public function createExam(StoreExamRequest $request)
    {
        return Exam::query()->create([
            'examID' => $this->IDGenerateService->examID(),
            'exam' => data_get($request, 'exam'),
            'totalMark' => data_get($request, 'totalMark'),
            'date' => data_get($request, 'date'),
            'classID' => data_get($request, 'classID'),
            'subjectID' => data_get($request, 'subjectID'),
            'categoryID' => data_get($request, 'categoryID'),
            'branchID' => data_get($request, 'branchID'),
        ]);
    }

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamById(Exam $exam)
    {
        return Exam::query()->find($exam);
    }

    /**
     * @param UpdateExamRequest $request
     * @param Exam $exam
     * @return mixed
     * @throws Exception
     */
    public function updateExam(UpdateExamRequest $request, Exam $exam)
    {
        $updated = $exam->update([
            'exam' => data_get($request, 'exam', $exam->exam),
            'totalMark' => data_get($request, 'totalMark', $exam->totalMark),
            'date' => data_get($request, 'date', $exam->date),
            'classID' => data_get($request, 'classID', $exam->classID),
            'subjectID' => data_get($request, 'subjectID', $exam->subjectID),
            'categoryID' => data_get($request, 'categoryID', $exam->categoryID),
            'branchID' => data_get($request, 'branchID', $exam->branchID),
        ]);

        if (!$updated) {
            throw new Exception('Failed to update Exam: ' . $exam->examID);
        }

        return $exam;
    }

    /**
     * @param Exam $exam
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteExam(Exam $exam)
    {
        $deleted = $exam->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Exam: ' . $exam->examID);
        }

        return $deleted;
    }
}
