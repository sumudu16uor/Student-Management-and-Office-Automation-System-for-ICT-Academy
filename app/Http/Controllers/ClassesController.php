<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesCollection;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Http\Requests\StoreClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Repositories\Interfaces\ClassesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassesController extends Controller
{
    /**
     * @var ClassesRepositoryInterface
     */
    private ClassesRepositoryInterface $classesRepository;

    /**
     * @param ClassesRepositoryInterface $classesRepository
     */
    public function __construct(ClassesRepositoryInterface $classesRepository)
    {
        $this->classesRepository = $classesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ClassesCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['required', 'string', 'max:10', Rule::in(['Active', 'Deactivate'])],
            'feeType' => ['nullable', 'string', 'max:8', Rule::in(['Daily', 'Monthly'])],
            'day' => ['nullable', Rule::in(['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']), 'string', 'max:10']
        ]);

        $classes = $this->classesRepository->getAllClasses($request);

        return new ClassesCollection($classes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClassesRequest $request
     * @return ClassesResource
     */
    public function store(StoreClassesRequest $request)
    {
        $created = $this->classesRepository->createClass($request);

        return new ClassesResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Classes $class
     * @return ClassesCollection
     */
    public function show(Classes $class)
    {
        $class = $this->classesRepository->getClassById($class);

        return new ClassesCollection($class);
    }

    /**
     * Display the specified resource.
     *
     * @param Classes $class
     * @return ClassesCollection
     */
    public function showExams(Classes $class)
    {
        $class = $this->classesRepository->getExamsByClassId($class);

        return new ClassesCollection($class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClassesRequest $request
     * @param Classes $class
     * @return ClassesResource
     */
    public function update(UpdateClassesRequest $request, Classes $class)
    {
        $updated = $this->classesRepository->updateClass($request, $class);

        return new ClassesResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Classes $class
     * @return JsonResponse
     */
    public function destroy(Classes $class)
    {
        $deleted = $this->classesRepository->forceDeleteClass($class);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new ClassesResource($class),
        ]);
    }
}
