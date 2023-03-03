<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentRepository implements StudentRepositoryInterface
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
     * @throws Exception
     */
    public function getAllStudents(Request $request)
    {
        return Student::query()->with(['person', 'parent'])
            ->join('people', 'students.studentID', 'people.personID')
            ->where('people.status', data_get($request, 'status'))
            ->get();
    }

    /**
     * @param StoreStudentRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function createStudent(StoreStudentRequest $request)
    {
        return DB::transaction(function () use ($request){

            DB::statement('SET foreign_key_checks = 0;');

            $student = Student::query()->create([
                'studentID' => $this->IDGenerateService->studentID(data_get($request, 'dob')),
                'grade' => data_get($request, 'grade'),
                'branchID' => data_get($request, 'branchID'),
            ]);

            $student->person()->create([
                'firstName' => data_get($request, 'firstName'),
                'lastName' => data_get($request, 'lastName'),
                'dob' => data_get($request, 'dob'),
                'sex' => data_get($request, 'sex'),
                'telNo' => data_get($request, 'telNo'),
                'address' => data_get($request, 'address'),
                'email' => data_get($request, 'email'),
                'status' => data_get($request, 'status'),
                'joinedDate' => data_get($request, 'joinedDate'),
            ]);

            $student->parent()->create([
                'title' => data_get($request, 'title'),
                'parentName' => data_get($request, 'parentName'),
                'parentType' => data_get($request, 'parentType'),
                'telNo' => data_get($request, 'parentTelNo'),
            ]);

            DB::statement('SET foreign_key_checks = 1;');

            return $student;
        });
    }

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentById(Student $student)
    {
        return Student::query()->find($student);
    }

    /**
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function updateStudent(UpdateStudentRequest $request, Student $student)
    {
        return DB::transaction(function () use ($request, $student){

            $student->update([
                'grade' => data_get($request, 'grade', $student->grade),
                'branchID' => data_get($request, 'branchID', $student->branchID),
            ]);

            $student->person->update([
                'firstName' => data_get($request, 'firstName', $student->person->firstName),
                'lastName' => data_get($request, 'lastName', $student->person->lastName),
                'dob' => data_get($request, 'dob', $student->person->dob),
                'sex' => data_get($request, 'sex', $student->person->sex),
                'telNo' => data_get($request, 'telNo', $student->person->telNo),
                'address' => data_get($request, 'address', $student->person->address),
                'email' => data_get($request, 'email', $student->person->email),
                'status' => data_get($request, 'status', $student->person->status),
                'joinedDate' => data_get($request, 'joinedDate', $student->person->joinedDate),
            ]);

            $updated = $student->parent->update([
                'title' => data_get($request, 'title', $student->person->title),
                'parentName' => data_get($request, 'parentName', $student->person->parentName),
                'parentType' => data_get($request, 'parentType', $student->person->parentType),
                'telNo' => data_get($request, 'parentTelNo', $student->person->parentTelNo),
            ]);

            if (!$updated){
                throw new Exception('Failed to update Student: ' . $student->studentID);
            }

            return $student;
        });
    }

    /**
     * @param Student $student
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function forceDeleteStudent(Student $student)
    {
        return DB::transaction(function () use ($student) {

            $student->parent->delete();

            $student->delete();

            $deleted = $student->person->delete();

            if (!$deleted) {
                throw new Exception('Failed to delete Student: ' . $student->studentID);
            }

            return $deleted;
        });
    }
}
