<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

interface EmployeeRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllEmployees(Request $request);

    /**
     * @param StoreEmployeeRequest $request
     * @return mixed
     */
    public function createEmployee(StoreEmployeeRequest $request);

    /**
     * @param Employee $employee
     * @return mixed
     */
    public function getEmployeeById(Employee $employee);

    /**
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return mixed
     */
    public function updateEmployee(UpdateEmployeeRequest $request, Employee $employee);

    /**
     * @param Employee $employee
     * @return mixed
     */
    public function forceDeleteEmployee(Employee $employee);
}
