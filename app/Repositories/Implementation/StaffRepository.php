<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StaffRepository implements StaffRepositoryInterface
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
    public function getAllStaffs(Request $request)
    {
        return Staff::query()->with(['employee', 'employee.person'])
            ->join('employees', 'staff.staffID', 'employees.employeeID')
            ->join('people', 'employees.employeeID', 'people.personID')
            ->where('people.status', data_get($request, 'status'))
            ->get();
    }

    /**
     * @param StoreStaffRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function createStaff(StoreStaffRequest $request)
    {
        return DB::transaction( function () use ($request){

            DB::statement('SET foreign_key_checks = 0;');

            $staff = Staff::query()->create([
                'staffID' => $this->IDGenerateService->staffID(),
                'branchID' => data_get($request, 'branchID'),
            ]);

            $staff->employee()->create([
                'nic' => data_get($request, 'nic'),
                'title' => data_get($request, 'title'),
            ]);

            $staff->employee->person()->create([
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

            return $staff;
        });
    }

    /**
     * @param Staff $staff
     * @return mixed
     */
    public function getStaffById(Staff $staff)
    {
        return Staff::query()->find($staff);
    }

    /**
     * @param UpdateStaffRequest $request
     * @param Staff $staff
     * @return mixed
     * @throws Throwable
     */
    public function updateStaff(UpdateStaffRequest $request, Staff $staff)
    {
        return DB::transaction(function () use ($request, $staff){

            $staff->update([
                'branchID' => data_get($request, 'branchID', $staff->branchID),
            ]);

            $staff->employee->update([
                'nic' => data_get($request, 'nic',$staff->employee->nic),
                'title' => data_get($request, 'title', $staff->employee->title),
            ]);

            $updated = $staff->employee->person->update([
                'firstName' => data_get($request, 'firstName', $staff->employee->person->firstName),
                'lastName' => data_get($request, 'lastName', $staff->employee->person->lastName),
                'dob' => data_get($request, 'dob', $staff->employee->person->dob),
                'sex' => data_get($request, 'sex', $staff->employee->person->sex),
                'telNo' => data_get($request, 'telNo', $staff->employee->person->telNo),
                'address' => data_get($request, 'address', $staff->employee->person->address),
                'email' => data_get($request, 'email', $staff->employee->person->email),
                'status' => data_get($request, 'status', $staff->employee->person->status),
                'joinedDate' => data_get($request, 'joinedDate', $staff->employee->person->joinedDate),
            ]);

            if (!$updated){
                throw new Exception('Failed to update Staff: ' . $staff->staffID);
            }

            return $staff;
        });
    }

    /**
     * @param Staff $staff
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function forceDeleteStaff(Staff $staff)
    {
        return DB::transaction(function () use ($staff){

            $staff->delete();

            $staff->employee->delete();

            $deleted = $staff->employee->person->delete();

            if (!$deleted) {
                throw new Exception('Failed to delete Staff: ' . $staff->staffID);
            }

            return $deleted;
        });
    }
}
