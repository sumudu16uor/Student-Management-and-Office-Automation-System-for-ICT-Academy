<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenditureCollection;
use App\Http\Resources\ExpenditureResource;
use App\Models\Expenditure;
use App\Http\Requests\StoreExpenditureRequest;
use App\Http\Requests\UpdateExpenditureRequest;
use App\Repositories\Interfaces\ExpenditureRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenditureController extends Controller
{
    /**
     * @var ExpenditureRepositoryInterface
     */
    private ExpenditureRepositoryInterface $expenditureRepository;

    /**
     * @param ExpenditureRepositoryInterface $expenditureRepository
     */
    public function __construct(ExpenditureRepositoryInterface $expenditureRepository)
    {
        $this->expenditureRepository = $expenditureRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ExpenditureCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date']
        ]);

        $expenditures = $this->expenditureRepository->getAllExpenditures($request);

        return new ExpenditureCollection($expenditures);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ExpenditureCollection
     */
    public function indexTrashed(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date']
        ]);

        $expenditures = $this->expenditureRepository->getAllTrashedExpenditures($request);

        return new ExpenditureCollection($expenditures);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreExpenditureRequest $request
     * @return ExpenditureResource
     */
    public function store(StoreExpenditureRequest $request)
    {
        $created = $this->expenditureRepository->createExpenditure($request);

        return new ExpenditureResource($created);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $expenditureID
     * @return JsonResponse
     */
    public function restore(Request $request, $expenditureID)
    {
        $request->validate([
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8']
        ]);

        $restored = $this->expenditureRepository->trashedRestore($request, $expenditureID);

        return new JsonResponse([
            'success' => $restored,
            'status' => $restored ? 'restored' : 'Failed'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Expenditure $expenditure
     * @return ExpenditureCollection
     */
    public function show(Expenditure $expenditure)
    {
        $expenditure = $this->expenditureRepository->getExpenditureById($expenditure);

        return new ExpenditureCollection($expenditure);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateExpenditureRequest $request
     * @param Expenditure $expenditure
     * @return ExpenditureResource
     */
    public function update(UpdateExpenditureRequest $request, Expenditure $expenditure)
    {
        $updated = $this->expenditureRepository->updateExpenditure($request, $expenditure);

        return new ExpenditureResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Expenditure $expenditure
     * @return JsonResponse
     */
    public function destroy(Request $request, Expenditure $expenditure)
    {
        $request->validate([
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8']
        ]);

        $deleted = $this->expenditureRepository->softDeleteExpenditure($request, $expenditure);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new ExpenditureResource($expenditure),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $expenditureID
     * @return JsonResponse
     */
    public function destroyTrashed($expenditureID)
    {
        $deleted = $this->expenditureRepository->forceDeleteExpenditure($expenditureID);

        return new JsonResponse([
            'success' => $deleted,
            'status' => $deleted ? 'permanently_deleted' : 'Failed'
        ]);
    }
}
