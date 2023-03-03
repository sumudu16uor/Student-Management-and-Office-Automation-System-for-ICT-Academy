<?php

namespace App\Services\Implementation\IDGenerate;

use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use App\Services\Interfaces\IDGenerate\IDGeneratorQueryServiceInterface;
use App\Services\Interfaces\IDGenerate\IDGeneratorServiceInterface;

class IDGenerateService implements IDGenerateServiceInterface
{
    /**
     * @var IDGeneratorServiceInterface
     */
    private IDGeneratorServiceInterface $generatorService;

    /**
     * @param IDGeneratorQueryService $queryService
     */
    private IDGeneratorQueryServiceInterface $queryService;

    public function __construct(IDGeneratorServiceInterface $generatorService, IDGeneratorQueryServiceInterface $queryService)
    {
        $this->generatorService = $generatorService;
        $this->queryService = $queryService;
    }

    /**
     * @return string
     */
    public function branchID(): string
    {
        return $this->generatorService->generateID(self::BRANCH, $this->queryService->getBranchIDs());
    }

    /**
     * @return string
     */
    public function staffID(): string
    {
        return $this->generatorService->generateID(self::STAFF, $this->queryService->getStaffIDs());
    }

    /**
     * @return string
     */
    public function teacherID(): string
    {
        return $this->generatorService->generateID(self::TEACHER, $this->queryService->getTeacherIDs());
    }

    /**
     * @return string
     */
    public function userID(): string
    {
        return $this->generatorService->generateID(self::USER, $this->queryService->getUserIDs());
    }

    /**
     * @param string $dob
     * @return string
     */
    public function studentID(string $dob): string
    {
        return $this->generatorService->generateStudentID($dob, $this->queryService->getStudentIDs());
    }

    /**
     * @return string
     */
    public function categoryID(): string
    {
        return $this->generatorService->generateID(self::CATEGORY, $this->queryService->getCategoryIDs());
    }

    /**
     * @return string
     */
    public function subjectID(): string
    {
        return $this->generatorService->generateID(self::SUBJECT, $this->queryService->getSubjectIDs());
    }

    /**
     * @return string
     */
    public function classID(): string
    {
        return $this->generatorService->generateID(self::CLASSES, $this->queryService->getClassIDs());
    }

    /**
     * @return string
     */
    public function examID(): string
    {
        return $this->generatorService->generateID(self::EXAM, $this->queryService->getExamIDs());
    }
}
