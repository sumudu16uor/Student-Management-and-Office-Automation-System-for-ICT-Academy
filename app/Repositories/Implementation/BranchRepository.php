<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;

class BranchRepository implements BranchRepositoryInterface
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
     * @return mixed
     */
    public function getAllBranches()
    {
        return Branch::query()->get();
    }

    /**
     * @param StoreBranchRequest $request
     * @return mixed
     */
    public function createBranch(StoreBranchRequest $request)
    {
        return Branch::query()->create([
            'branchID' => $this->IDGenerateService->branchID(),
            'branchName' => data_get($request, 'branchName'),
            'telNo' => data_get($request, 'telNo'),
            'address' => data_get($request, 'address'),
            'noOfRooms' => data_get($request, 'noOfRooms'),
        ]);
    }

    /**
     * @param Branch $branch
     * @return mixed
     */
    public function getBranchById(Branch $branch)
    {
        return Branch::query()->find($branch);
    }

    /**
     * @param UpdateBranchRequest $request
     * @param Branch $branch
     * @return mixed
     * @throws Exception
     */
    public function updateBranch(UpdateBranchRequest $request, Branch $branch)
    {
        $updated = $branch->update([
            'branchName' => data_get($request, 'branchName', $branch->branchName),
            'telNo' => data_get($request, 'telNo', $branch->telNo),
            'address' => data_get($request, 'address', $branch->address),
            'noOfRooms' => data_get($request, 'noOfRooms', $branch->noOfRooms),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Branch: ' . $branch->branchID);
        }

        return $branch;
    }

    /**
     * @param Branch $branch
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteBranch(Branch $branch)
    {
        $deleted = $branch->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Branch: ' . $branch->branchID);
        }

        return $deleted;
    }
}
