<?php

namespace App\Services\Interfaces\IDGenerate;

use Illuminate\Support\Collection;

interface IDGeneratorQueryServiceInterface
{
    public function getBranchIDs(): Collection;
    public function getStaffIDs(): Collection;
    public function getTeacherIDs(): Collection;
    public function getUserIDs(): Collection;
    public function getStudentIDs(): Collection;
    public function getStudentIDsByDOB(string $dob): Collection;
    public function getCategoryIDs(): Collection;
    public function getSubjectIDs(): Collection;
    public function getClassIDs(): Collection;
    public function getExamIDs(): Collection;

}
