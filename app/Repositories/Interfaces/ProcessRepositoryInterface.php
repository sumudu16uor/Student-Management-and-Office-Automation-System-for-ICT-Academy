<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Models\Process;
use Illuminate\Http\Request;

interface ProcessRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllProcesses(Request $request);

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     */
    public function monthEndProcess(StoreProcessRequest $request);

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     */
    public function yearEndProcess(StoreProcessRequest $request);

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     */
    public function ordinaryLevelEndProcess(StoreProcessRequest $request);

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     */
    public function advancedLevelEndProcess(StoreProcessRequest $request);

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     */
    public function clearLoginHistory(StoreProcessRequest $request);

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     */
    public function reverseMonthEndProcess(StoreProcessRequest $request, Process $process);

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     */
    public function reverseYearEndProcess(StoreProcessRequest $request, Process $process);

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     */
    public function reverseOrdinaryLevelEndProcess(StoreProcessRequest $request, Process $process);

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     */
    public function reverseAdvancedLevelEndProcess(StoreProcessRequest $request, Process $process);

    /**
     * @param Process $process
     * @return mixed
     */
    public function getProcessById(Process $process);

    /**
     * @return mixed
     */
    public function getMonthEndProcess();

    /**
     * @return mixed
     */
    public function getYearEndProcess();

    /**
     * @return mixed
     */
    public function getOrdinaryLevelEndProcess();

    /**
     * @return mixed
     */
    public function getAdvancedLevelEndProcess();

    /**
     * @return mixed
     */
    public function getClearLogin();

    /**
     * @param UpdateProcessRequest $request
     * @param Process $process
     * @return mixed
     */
    public function updateProcess(UpdateProcessRequest $request, Process $process);

    /**
     * @param $processID
     * @return mixed
     */
    public function forceDeleteProcess($processID);
}
