<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchCollection;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    /**
     * @var BranchRepositoryInterface
     */
    private BranchRepositoryInterface $branchRepository;

    /**
     * @param BranchRepositoryInterface $branchRepository
     */
    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return BranchCollection
     */
    public function index()
    {
        $branches = $this->branchRepository->getAllBranches();

        return new BranchCollection($branches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBranchRequest $request
     * @return BranchResource
     */
    public function store(StoreBranchRequest $request)
    {
        $created = $this->branchRepository->createBranch($request);

        return new BranchResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Branch $branch
     * @return BranchCollection
     */
    public function show(Branch $branch)
    {
        $branch = $this->branchRepository->getBranchById($branch);

        return new BranchCollection($branch);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBranchRequest $request
     * @param Branch $branch
     * @return BranchResource
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $updated = $this->branchRepository->updateBranch($request, $branch);

        return new BranchResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Branch $branch
     * @return JsonResponse
     */
    public function destroy(Branch $branch)
    {
        $deleted = $this->branchRepository->forceDeleteBranch($branch);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new BranchResource($branch),
        ]);
    }
}
