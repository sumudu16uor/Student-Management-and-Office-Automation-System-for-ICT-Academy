<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;

interface BranchRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllBranches();

    /**
     * @param StoreBranchRequest $request
     * @return mixed
     */
    public function createBranch(StoreBranchRequest $request);

    /**
     * @param Branch $branch
     * @return mixed
     */
    public function getBranchById(Branch $branch);

    /**
     * @param UpdateBranchRequest $request
     * @param Branch $branch
     * @return mixed
     */
    public function updateBranch(UpdateBranchRequest $request, Branch $branch);

    /**
     * @param Branch $branch
     * @return mixed
     */
    public function forceDeleteBranch(Branch $branch);
}
