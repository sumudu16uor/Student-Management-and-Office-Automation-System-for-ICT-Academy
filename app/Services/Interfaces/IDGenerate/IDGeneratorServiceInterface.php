<?php

namespace App\Services\Interfaces\IDGenerate;

use Illuminate\Support\Collection;

interface IDGeneratorServiceInterface
{
    public function generateID(string $prefix, Collection $currentIDs): string;
    public function generateStudentID(string $dob, Collection $currentIDs): string;
}
