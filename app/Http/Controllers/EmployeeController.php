<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private EmployeeRepositoryInterface $employeeRepository;

    /**
     * @param EmployeeRepositoryInterface $employeeRepository
     */
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return EmployeeCollection
     */
    public function index(Request $request)
    {
        $request->validate(['status' => ['required', 'string', 'max:10',
            Rule::in(['Active', 'Deactivate'])]]);

        $employees = $this->employeeRepository->getAllEmployees($request);

        return new EmployeeCollection($employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @return EmployeeResource
     */
    public function store(StoreEmployeeRequest $request)
    {
        $created = $this->employeeRepository->createEmployee($request);

        return new EmployeeResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return EmployeeCollection
     */
    public function show(Employee $employee)
    {
        $employee = $this->employeeRepository->getEmployeeById($employee);

        return new EmployeeCollection($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $updated = $this->employeeRepository->updateEmployee($request, $employee);

        return new EmployeeResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(Employee $employee)
    {
        $deleted = $this->employeeRepository->forceDeleteEmployee($employee);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new EmployeeResource($employee),
        ]);
    }
}
