<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Models\Classes;
use App\Repositories\Interfaces\ClassesRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Http\Request;

class ClassesRepository implements ClassesRepositoryInterface
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
    public function getAllClasses(Request $request)
    {
        if ($request->feeType != null && $request->day != null) {
            return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
                ->withCount('students')
                ->where('status', data_get($request, 'status'))
                ->where('feeType', data_get($request, 'feeType'))
                ->where('day', data_get($request, 'day'))
                ->get();
        }

        if ($request->feeType != null) {
            return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
                ->withCount('students')
                ->where('status', data_get($request, 'status'))
                ->where('feeType', data_get($request, 'feeType'))
                ->get();
        }

        if ($request->day != null) {
            return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
                ->withCount('students')
                ->where('status', data_get($request, 'status'))
                ->where('day', data_get($request, 'day'))
                ->get();
        }

        return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
            ->withCount('students')
            ->where('status', data_get($request, 'status'))
            ->get();
    }

    /**
     * @param StoreClassesRequest $request
     * @return mixed
     */
    public function createClass(StoreClassesRequest $request)
    {
        $class = Classes::query()->create([
            'classID' => $this->IDGenerateService->classID(),
            'className' => data_get($request, 'className'),
            'day' => data_get($request, 'day'),
            'startTime' => data_get($request, 'startTime'),
            'endTime' => data_get($request, 'endTime'),
            'grade' => data_get($request, 'grade'),
            'room' => data_get($request, 'room'),
            'classFee' => data_get($request, 'classFee'),
            'feeType' => data_get($request, 'feeType'),
            'status' => data_get($request, 'status'),
            'subjectID' => data_get($request, 'subjectID'),
            'categoryID' => data_get($request, 'categoryID'),
            'teacherID' => data_get($request, 'teacherID'),
            'branchID' => data_get($request, 'branchID'),
        ]);

        return Classes::query()->with('subject')->find(data_get($class, 'classID'));
    }

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getClassById(Classes $class)
    {
        return Classes::query()->with(['subject', 'category', 'teacher', 'branch'])
            ->withCount('students')
            ->find($class);
    }

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getExamsByClassId(Classes $class)
    {
        return Classes::query()->with(['exams', 'subject'])
            ->withCount('students')
            ->find($class);
    }

    /**
     * @param UpdateClassesRequest $request
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function updateClass(UpdateClassesRequest $request, Classes $class)
    {
        $updated = $class->update([
            'className' => data_get($request, 'className', $class->className),
            'day' => data_get($request, 'day', $class->day),
            'startTime' => data_get($request, 'startTime', $class->startTime),
            'endTime' => data_get($request, 'endTime', $class->endTime),
            'grade' => data_get($request, 'grade', $class->grade),
            'room' => data_get($request, 'room', $class->room),
            'classFee' => data_get($request, 'classFee', $class->classFee),
            'feeType' => data_get($request, 'feeType', $class->feeType),
            'status' => data_get($request, 'status', $class->status),
            'subjectID' => data_get($request, 'subjectID', $class->subjectID),
            'categoryID' => data_get($request, 'categoryID', $class->categoryID),
            'teacherID' => data_get($request, 'teacherID', $class->teacherID),
            'branchID' => data_get($request, 'branchID', $class->branchID),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Class: ' . $class->classID);
        }

        return Classes::query()->with('subject')->find(data_get($class, 'classID'));
    }

    /**
     * @param Classes $class
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteClass(Classes $class)
    {
        $deleted = $class->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Class: ' . $class->classID);
        }

        return $deleted;
    }
}
