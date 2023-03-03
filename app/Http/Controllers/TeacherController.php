<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherCollection;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * @var TeacherRepositoryInterface
     */
    private TeacherRepositoryInterface $teacherRepository;

    /**
     * @param TeacherRepositoryInterface $teacherRepository
     */
    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TeacherCollection
     */
    public function index(Request $request)
    {
        $request->validate(['status' => ['required', 'string', 'max:10',
            Rule::in(['Active', 'Deactivate'])]]);

        $teachers = $this->teacherRepository->getAllTeachers($request);

        return new TeacherCollection($teachers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTeacherRequest $request
     * @return TeacherResource
     */
    public function store(StoreTeacherRequest $request)
    {
        $created = $this->teacherRepository->createTeacher($request);

        DB::statement('SET foreign_key_checks = 1;');

        return new TeacherResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Teacher $teacher
     * @return TeacherCollection
     */
    public function show(Teacher $teacher)
    {
        $teacher = $this->teacherRepository->getTeacherById($teacher);

        return new TeacherCollection($teacher);
    }

    /**
     * Display the specified resource.
     *
     * @param Teacher $teacher
     * @return TeacherResource
     */
    public function showClassesWithExams(Teacher $teacher)
    {
        $teacher = $this->teacherRepository->getClassesWithExamsByTeacherId($teacher);

        return new TeacherResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return TeacherResource
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $updated = $this->teacherRepository->updateTeacher($request, $teacher);

        return new TeacherResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function destroy(Teacher $teacher)
    {
        $deleted = $this->teacherRepository->forceDeleteTeacher($teacher);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new TeacherResource($teacher),
        ]);
    }
}
