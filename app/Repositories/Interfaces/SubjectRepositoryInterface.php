<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;

interface SubjectRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllSubjects();

    /**
     * @param StoreSubjectRequest $request
     * @return mixed
     */
    public function createSubject(StoreSubjectRequest $request);

    /**
     * @param Subject $subject
     * @return mixed
     */
    public function getSubjectById(Subject $subject);

    /**
     * @param Subject $subject
     * @return mixed
     */
    public function getClassesBySubjectId(Subject $subject);

    /**
     * @param UpdateSubjectRequest $request
     * @param Subject $subject
     * @return mixed
     */
    public function updateSubject(UpdateSubjectRequest $request, Subject $subject);

    /**
     * @param Subject $subject
     * @return mixed
     */
    public function forceDeleteSubject(Subject $subject);
}
