<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;

class SubjectRepository implements SubjectRepositoryInterface
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
     * @return mixed
     * @throws Exception
     */
    public function getAllSubjects()
    {
        return Subject::query()->with('category')->get();
    }

    /**
     * @param StoreSubjectRequest $request
     * @return mixed
     */
    public function createSubject(StoreSubjectRequest $request)
    {
        $subject = Subject::query()->create([
            'subjectID' => $this->IDGenerateService->subjectID(),
            'subjectName' => data_get($request, 'subjectName'),
            'medium' => data_get($request, 'medium'),
            'categoryID' => data_get($request, 'categoryID'),
        ]);

        return Subject::query()->with('category')->find(data_get($subject, 'subjectID'));
    }

    /**
     * @param Subject $subject
     * @return mixed
     */
    public function getSubjectById(Subject $subject)
    {
        return Subject::query()->with('category')->find($subject);
    }

    /**
     * @param Subject $subject
     * @return mixed
     */
    public function getClassesBySubjectId(Subject $subject)
    {
        return Subject::query()->with('classes')->find($subject);
    }

    /**
     * @param UpdateSubjectRequest $request
     * @param Subject $subject
     * @return mixed
     * @throws Exception
     */
    public function updateSubject(UpdateSubjectRequest $request, Subject $subject)
    {
        $updated = $subject->update([
            'subjectName' => data_get($request, 'subjectName', $subject->subjectName),
            'medium' => data_get($request, 'medium', $subject->medium),
            'categoryID' => data_get($request, 'categoryID', $subject->categoryID),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Subject: ' . $subject->subjectID);
        }

        return Subject::query()->with('category')->find(data_get($subject, 'subjectID'));
    }

    /**
     * @param Subject $subject
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteSubject(Subject $subject)
    {
        $deleted = $subject->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Subject: ' . $subject->subjectID);
        }

        return $deleted;
    }
}
