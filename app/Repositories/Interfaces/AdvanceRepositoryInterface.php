<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreAdvanceRequest;
use App\Http\Requests\UpdateAdvanceRequest;
use App\Models\Advance;
use Illuminate\Http\Request;

interface AdvanceRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllAdvances(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTrashedAdvances(Request $request);

    /**
     * @param StoreAdvanceRequest $request
     * @return mixed
     */
    public function createAdvance(StoreAdvanceRequest $request);

    /**
     * @param Request $request
     * @param $advanceID
     * @return mixed
     */
    public function trashedRestore(Request $request, $advanceID);

    /**
     * @param Advance $advance
     * @return mixed
     */
    public function getAdvanceById(Advance $advance);

    /**
     * @param UpdateAdvanceRequest $request
     * @param Advance $advance
     * @return mixed
     */
    public function updateAdvance(UpdateAdvanceRequest $request, Advance $advance);

    /**
     * @param Request $request
     * @param Advance $advance
     * @return mixed
     */
    public function softDeleteAdvance(Request $request, Advance $advance);

    /**
     * @param $advanceID
     * @return mixed
     */
    public function forceDeleteAdvance($advanceID);
}
