<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdvanceCollection;
use App\Http\Resources\AdvanceResource;
use App\Models\Advance;
use App\Http\Requests\StoreAdvanceRequest;
use App\Http\Requests\UpdateAdvanceRequest;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdvanceController extends Controller
{
    /**
     * @var AdvanceRepositoryInterface
     */
    private AdvanceRepositoryInterface $advanceRepository;

    /**
     * @param AdvanceRepositoryInterface $advanceRepository
     */
    public function __construct(AdvanceRepositoryInterface $advanceRepository)
    {
        $this->advanceRepository = $advanceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AdvanceCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'employeeType' => ['required', 'string', 'max:20', Rule::exists('employees', 'employeeType')],
            'date' => ['nullable', 'date']
        ]);

        $advances = $this->advanceRepository->getAllAdvances($request);

        return new AdvanceCollection($advances);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AdvanceCollection
     */
    public function indexTrashed(Request $request)
    {
        $request->validate([
            'employeeType' => ['required', 'string', 'max:20', Rule::exists('employees', 'employeeType')],
            'date' => ['nullable', 'date']
        ]);

        $advances = $this->advanceRepository->getAllTrashedAdvances($request);

        return new AdvanceCollection($advances);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvanceRequest $request
     * @return AdvanceResource
     */
    public function store(StoreAdvanceRequest $request)
    {
        $created = $this->advanceRepository->createAdvance($request);

        return new AdvanceResource($created);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $advanceID
     * @return JsonResponse
     */
    public function restore(Request $request, $advanceID)
    {
        $request->validate([
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8']
        ]);

        $restored = $this->advanceRepository->trashedRestore($request, $advanceID);

        return new JsonResponse([
            'success' => $restored,
            'status' => $restored ? 'restored' : 'Failed'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Advance $advance
     * @return AdvanceCollection
     */
    public function show(Advance $advance)
    {
        $advance = $this->advanceRepository->getAdvanceById($advance);

        return new AdvanceCollection($advance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvanceRequest $request
     * @param Advance $advance
     * @return AdvanceResource
     */
    public function update(UpdateAdvanceRequest $request, Advance $advance)
    {
        $updated = $this->advanceRepository->updateAdvance($request, $advance);

        return new AdvanceResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Advance $advance
     * @return JsonResponse
     */
    public function destroy(Request $request, Advance $advance)
    {
        $request->validate([
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8']
        ]);

        $deleted = $this->advanceRepository->softDeleteAdvance($request, $advance);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new AdvanceResource($advance),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $advanceID
     * @return JsonResponse
     */
    public function destroyTrashed($advanceID)
    {
        $deleted = $this->advanceRepository->forceDeleteAdvance($advanceID);

        return new JsonResponse([
            'success' => $deleted,
            'status' => $deleted ? 'permanently_deleted' : 'Failed'
        ]);
    }
}
