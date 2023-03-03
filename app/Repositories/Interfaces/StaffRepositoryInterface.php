<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

interface StaffRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllStaffs(Request $request);

    /**
     * @param StoreStaffRequest $request
     * @return mixed
     */
    public function createStaff(StoreStaffRequest $request);

    /**
     * @param Staff $staff
     * @return mixed
     */
    public function getStaffById(Staff $staff);

    /**
     * @param UpdateStaffRequest $request
     * @param Staff $staff
     * @return mixed
     */
    public function updateStaff(UpdateStaffRequest $request, Staff $staff);

    /**
     * @param Staff $staff
     * @return mixed
     */
    public function forceDeleteStaff(Staff $staff);
}
