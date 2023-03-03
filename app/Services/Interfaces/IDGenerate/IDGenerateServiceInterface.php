<?php

namespace App\Services\Interfaces\IDGenerate;


interface IDGenerateServiceInterface
{
    const BRANCH = 'BRNCH';
    const STAFF = 'STAFF';
    const TEACHER = 'TECHR';
    const USER = 'USERS';
    const STUDENT = 'ICTA';
    const CATEGORY = 'CTGRY';
    const SUBJECT = 'SUBJT';
    const CLASSES = 'CLASS';
    const EXAM = 'EXAM';

    public function branchID(): string;
    public function staffID(): string;
    public function teacherID(): string;
    public function userID(): string;
    public function studentID(string $dob): string;
    public function categoryID(): string;
    public function subjectID(): string;
    public function classID(): string;
    public function examID(): string;
}
