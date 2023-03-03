<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    /**
     * @var SubjectRepositoryInterface
     */
    private SubjectRepositoryInterface $subjectRepository;

    /**
     * @param SubjectRepositoryInterface $subjectRepository
     */
    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return SubjectCollection
     */
    public function index()
    {
        $subjects = $this->subjectRepository->getAllSubjects();

        return new SubjectCollection($subjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubjectRequest $request
     * @return SubjectResource
     */
    public function store(StoreSubjectRequest $request)
    {
        $created = $this->subjectRepository->createSubject($request);

        return new SubjectResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Subject $subject
     * @return SubjectCollection
     */
    public function show(Subject $subject)
    {
        $subject = $this->subjectRepository->getSubjectById($subject);

        return new SubjectCollection($subject);
    }

    /**
     * Display the specified resource.
     *
     * @param Subject $subject
     * @return SubjectCollection
     */
    public function showClasses(Subject $subject)
    {
        $subject = $this->subjectRepository->getClassesBySubjectId($subject);

        return new SubjectCollection($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubjectRequest $request
     * @param Subject $subject
     * @return SubjectResource
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $updated = $this->subjectRepository->updateSubject($request, $subject);

        return new SubjectResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Subject $subject
     * @return JsonResponse
     */
    public function destroy(Subject $subject)
    {
        $deleted = $this->subjectRepository->forceDeleteSubject($subject);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => [
                'subjectID' => $subject->subjectID,
                'subjectName' => $subject->subjectName,
                'medium' => $subject->medium,
                'category' => new CategoryResource($subject->category),
            ],
        ]);
    }
}
