<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;

    /**
     * @param StudentRepositoryInterface $studentRepository
     */
    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return StudentCollection
     */
    public function index(Request $request)
    {
        $request->validate(['status' => ['required', 'string', 'max:10',
            Rule::in(['Active', 'Past'])]]);

        $students = $this->studentRepository->getAllStudents($request);

        return new StudentCollection($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStudentRequest $request
     * @return StudentResource
     */
    public function store(StoreStudentRequest $request)
    {
        $created = $this->studentRepository->createStudent($request);

        DB::statement('SET foreign_key_checks = 1;');

        return new StudentResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return StudentCollection
     */
    public function show(Student $student)
    {
        $student = $this->studentRepository->getStudentById($student);

        return new StudentCollection($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @return StudentResource
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $updated = $this->studentRepository->updateStudent($request, $student);

        return new StudentResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function destroy(Student $student)
    {
        $deleted = $this->studentRepository->forceDeleteStudent($student);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new StudentResource($student),
        ]);
    }
}
