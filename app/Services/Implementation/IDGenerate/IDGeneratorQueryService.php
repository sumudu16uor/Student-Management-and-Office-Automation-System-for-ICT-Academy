<?php

namespace App\Services\Implementation\IDGenerate;

use App\Services\Interfaces\IDGenerate\IDGeneratorQueryServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IDGeneratorQueryService implements IDGeneratorQueryServiceInterface
{

    /**
     * @return Collection
     */
    public function getBranchIDs(): Collection
    {
        return DB::table('branches')->orderBy('branchID', 'asc')->pluck('branchID');
    }

    /**
     * @return Collection
     */
    public function getStaffIDs(): Collection
    {
        return DB::table('staff')->orderBy('staffID', 'asc')->pluck('staffID');
    }

    /**
     * @return Collection
     */
    public function getTeacherIDs(): Collection
    {
        return DB::table('teachers')->orderBy('teacherID', 'asc')->pluck('teacherID');
    }

    /**
     * @return Collection
     */
    public function getUserIDs(): Collection
    {
        return DB::table('users')->orderBy('userID', 'asc')->pluck('userID');
    }

    /**
     * @return Collection
     */
    public function getStudentIDs(): Collection
    {
        return DB::table('students')->orderBy('studentID', 'asc')->pluck('studentID');
    }

    /**
     * @param string $dob
     * @return Collection
     */
    public function getStudentIDsByDOB(string $dob): Collection
    {
        return DB::table('people')->where('personType', 'Student')
            ->whereYear('dob', $dob)->orderBy('personID', 'asc')->pluck('personID');
    }

    /**
     * @return Collection
     */
    public function getCategoryIDs(): Collection
    {
        return DB::table('categories')->orderBy('categoryID', 'asc')->pluck('categoryID');
    }

    /**
     * @return Collection
     */
    public function getSubjectIDs(): Collection
    {
        return DB::table('subjects')->orderBy('subjectID', 'asc')->pluck('subjectID');
    }

    /**
     * @return Collection
     */
    public function getClassIDs(): Collection
    {
        return DB::table('classes')->orderBy('classID', 'asc')->pluck('classID');
    }

    /**
     * @return Collection
     */
    public function getExamIDs(): Collection
    {
        return DB::table('exams')->orderBy('examID', 'asc')->pluck('examID');
    }
}
