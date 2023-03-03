<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllEmployees(Request $request)
    {
        return Employee::query()->with(['employable', 'person'])
            ->join('people', 'employees.employeeID', 'people.personID')
            ->where('people.status', data_get($request, 'status'))
            ->get();
    }

    /**
     * @param StoreEmployeeRequest $request
     * @return mixed
     * @throws Exception
     */
    public function createEmployee(StoreEmployeeRequest $request)
    {
        throw new Exception('The HTTP request cannot be handled by the server.Not Implemented and Under Construction');
    }

    /**
     * @param Employee $employee
     * @return mixed
     */
    public function getEmployeeById(Employee $employee)
    {
        return Employee::query()->find($employee);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return mixed
     * @throws Exception
     */
    public function updateEmployee(UpdateEmployeeRequest $request, Employee $employee)
    {
        $updated = $employee->update([
            'nic' => data_get($request, 'nic', $employee->nic),
            'title' => data_get($request, 'title', $employee->title),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Employee: ' . $employee->employeeID);
        }

        return $employee;
    }

    /**
     * @param Employee $employee
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function forceDeleteEmployee(Employee $employee)
    {
        return DB::transaction(function () use ($employee){

            $employee->delete();

            $deleted = $employee->person->delete();

            if (!$deleted){
                throw new Exception('Failed to delete Employee: ' . $employee->employeeID);
            }

            return $deleted;
        });
    }
}
