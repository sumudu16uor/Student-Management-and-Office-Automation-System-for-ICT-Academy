<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TeacherRepository implements TeacherRepositoryInterface
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
    public function getAllTeachers(Request $request)
    {
        return Teacher::query()->with(['employee', 'employee.person'])
            ->join('employees', 'teachers.teacherID', 'employees.employeeID')
            ->join('people', 'employees.employeeID', 'people.personID')
            ->where('people.status', data_get($request, 'status'))
            ->get();
    }

    /**
     * @param StoreTeacherRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function createTeacher(StoreTeacherRequest $request)
    {
        return DB::transaction( function () use ($request){

            DB::statement('SET foreign_key_checks = 0;');

            $teacher = Teacher::query()->create([
                'teacherID' => $this->IDGenerateService->teacherID(),
                'qualification' => data_get($request, 'qualification'),
            ]);

            $teacher->employee()->create([
                'nic' => data_get($request, 'nic'),
                'title' => data_get($request, 'title'),
            ]);

            $teacher->employee->person()->create([
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

            DB::statement('SET foreign_key_checks = 1;');

            return $teacher;
        });
    }

    /**
     * @param Teacher $teacher
     * @return mixed
     */
    public function getTeacherById(Teacher $teacher)
    {
        return Teacher::query()->find($teacher);
    }

    /**
     * @param Teacher $teacher
     * @return mixed
     */
    public function getClassesWithExamsByTeacherId(Teacher $teacher)
    {
        return Teacher::query()
            ->with(['employee.person', 'classes.subject', 'classes.exams'])
            ->find($teacher->teacherID);
    }

    /**
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return mixed
     * @throws Throwable
     */
    public function updateTeacher(UpdateTeacherRequest $request, Teacher $teacher)
    {
        return DB::transaction(function () use ($request, $teacher){

            $teacher->update([
                'qualification' => data_get($request, 'qualification', $teacher->qualification),
            ]);

            $teacher->employee->update([
                'nic' => data_get($request, 'nic', $teacher->employee->nic),
                'title' => data_get($request, 'title', $teacher->employee->title),
            ]);

            $updated = $teacher->employee->person->update([
                'firstName' => data_get($request, 'firstName', $teacher->employee->person->firstName),
                'lastName' => data_get($request, 'lastName', $teacher->employee->person->lastName),
                'dob' => data_get($request, 'dob', $teacher->employee->person->dob),
                'sex' => data_get($request, 'sex', $teacher->employee->person->sex),
                'telNo' => data_get($request, 'telNo', $teacher->employee->person->telNo),
                'address' => data_get($request, 'address', $teacher->employee->person->address),
                'email' => data_get($request, 'email', $teacher->employee->person->email),
                'status' => data_get($request, 'status', $teacher->employee->person->status),
                'joinedDate' => data_get($request, 'joinedDate', $teacher->employee->person->joinedDate),
            ]);

            if (!$updated){
                throw new Exception('Failed to update Teacher: ' . $teacher->teacherID);
            }

            return $teacher;
        });
    }

    /**
     * @param Teacher $teacher
     * @return mixed
     * @throws Throwable
     */
    public function forceDeleteTeacher(Teacher $teacher)
    {
        return DB::transaction(function () use ($teacher){

            $teacher->delete();

            $teacher->employee->delete();

            $deleted = $teacher->employee->person->delete();

            if (!$deleted) {
                throw new Exception('Failed to delete Teacher: ' . $teacher->teacherID);
            }

            return $deleted;
        });
    }
}
