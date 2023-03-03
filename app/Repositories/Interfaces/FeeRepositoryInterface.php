<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Models\Classes;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

interface FeeRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllFees(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTrashedFees(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTodayFeesCollectionSummary(Request $request);

    /**
     * @param StoreFeeRequest $request
     * @param Student $student
     * @param Classes $class
     * @return mixed
     */
    public function payForOneClass(StoreFeeRequest $request, Student $student, Classes $class);

    /**
     * @param StoreFeeRequest $request
     * @param Student $student
     * @return mixed
     */
    public function payForManyClasses(StoreFeeRequest $request, Student $student);

    /**
     * @param Request $request
     * @param $feeID
     * @return mixed
     */
    public function trashedRestore(Request $request, $feeID);

    /**
     * @param Fee $fee
     * @return mixed
     */
    public function getFeeById(Fee $fee);

    /**
     * @param UpdateFeeRequest $request
     * @param Fee $fee
     * @return mixed
     */
    public function updateFee(UpdateFeeRequest $request, Fee $fee);

    /**
     * @param Request $request
     * @param Fee $fee
     * @return mixed
     */
    public function softDeleteFee(Request $request, Fee $fee);

    /**
     * @param $feeID
     * @return mixed
     */
    public function forceDeleteFee($feeID);
}
