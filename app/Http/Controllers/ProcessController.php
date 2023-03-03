<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProcessCollection;
use App\Http\Resources\ProcessResource;
use App\Models\Process;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Repositories\Interfaces\ProcessRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * @var ProcessRepositoryInterface
     */
    private ProcessRepositoryInterface $processRepository;

    /**
     * @param ProcessRepositoryInterface $processRepository
     */
    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ProcessCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y']
        ]);

        $processes = $this->processRepository->getAllProcesses($request);

        return new ProcessCollection($processes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function monthEnd(StoreProcessRequest $request)
    {
        $created = $this->processRepository->monthEndProcess($request);

        return new JsonResponse([
            'success' => $created != null,
            'process' => new ProcessResource($created)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function yearEnd(StoreProcessRequest $request)
    {
        $created = $this->processRepository->yearEndProcess($request);

        return new JsonResponse([
            'success' => $created != null,
            'process' => new ProcessResource($created)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function ordinaryLevelEnd(StoreProcessRequest $request)
    {
        $created = $this->processRepository->ordinaryLevelEndProcess($request);

        return new JsonResponse([
            'success' => $created != null,
            'process' => new ProcessResource($created)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function advancedLevelEnd(StoreProcessRequest $request)
    {
        $created = $this->processRepository->advancedLevelEndProcess($request);

        return new JsonResponse([
            'success' => $created != null,
            'process' => new ProcessResource($created)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @return JsonResponse
     */
    public function clearLogin(StoreProcessRequest $request)
    {
        $created = $this->processRepository->clearLoginHistory($request);

        return new JsonResponse([
            'success' => $created != null,
            'process' => new ProcessResource($created)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return JsonResponse
     */
    public function reverseMonthEnd(StoreProcessRequest $request, Process $process)
    {
        $reversed = $this->processRepository->reverseMonthEndProcess($request, $process);

        return new JsonResponse([
            'success' => $reversed,
            'status' => $reversed ? 'reversed' : 'Failed'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return JsonResponse
     */
    public function reverseYearEnd(StoreProcessRequest $request, Process $process)
    {
        $reversed = $this->processRepository->reverseYearEndProcess($request, $process);

        return new JsonResponse([
            'success' => $reversed,
            'status' => $reversed ? 'reversed' : 'Failed'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return JsonResponse
     */
    public function reverseOrdinaryLevelEnd(StoreProcessRequest $request, Process $process)
    {
        $reversed = $this->processRepository->reverseOrdinaryLevelEndProcess($request, $process);

        return new JsonResponse([
            'success' => $reversed,
            'status' => $reversed ? 'reversed' : 'Failed'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return JsonResponse
     */
    public function reverseAdvancedLevelEnd(StoreProcessRequest $request, Process $process)
    {
        $reversed = $this->processRepository->reverseAdvancedLevelEndProcess($request, $process);

        return new JsonResponse([
            'success' => $reversed,
            'status' => $reversed ? 'reversed' : 'Failed'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Process $process
     * @return ProcessResource
     */
    public function show(Process $process)
    {
        $process = $this->processRepository->getProcessById($process);

        return new ProcessResource($process);
    }

    /**
     * Display a listing of the resource.
     *
     * @return ProcessResource|JsonResponse
     */
    public function showMonthEnd()
    {
        $process = $this->processRepository->getMonthEndProcess();

        return $process != null ? new ProcessResource($process) : new JsonResponse(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse|ProcessResource
     */
    public function showYearEnd()
    {
        $yearEnd = $this->processRepository->getYearEndProcess();

        $ordinaryLevelEnd = $this->processRepository->getOrdinaryLevelEndProcess();

        return $ordinaryLevelEnd != null ?
            ($yearEnd != null ? new ProcessResource($yearEnd) : new JsonResponse(['success' => false])) :
            new JsonResponse(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse|ProcessResource
     */
    public function showOrdinaryLevelEnd()
    {
        $process = $this->processRepository->getOrdinaryLevelEndProcess();

        return $process != null ? new ProcessResource($process) : new JsonResponse(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse|ProcessResource
     */
    public function showAdvancedLevelEnd()
    {
        $process = $this->processRepository->getAdvancedLevelEndProcess();

        return $process != null ? new ProcessResource($process) : new JsonResponse(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @return ProcessResource|JsonResponse
     */
    public function showClearLogin()
    {
        $process = $this->processRepository->getClearLogin();

        return $process != null ? new ProcessResource($process) : new JsonResponse(['success' => false]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProcessRequest $request
     * @param Process $process
     * @return ProcessResource
     */
    public function update(UpdateProcessRequest $request, Process $process)
    {
        $updated = $this->processRepository->updateProcess($request, $process);

        return new ProcessResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $processID
     * @return JsonResponse
     */
    public function destroyTrashed($processID)
    {
        $deleted = $this->processRepository->forceDeleteProcess($processID);

        return new JsonResponse([
            'success' => $deleted,
            'status' => $deleted ? 'permanently_deleted' : 'Failed'
        ]);
    }
}
