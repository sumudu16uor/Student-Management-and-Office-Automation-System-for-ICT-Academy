<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreExpenditureRequest;
use App\Http\Requests\UpdateExpenditureRequest;
use App\Models\Expenditure;
use Illuminate\Http\Request;

interface ExpenditureRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExpenditures(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTrashedExpenditures(Request $request);

    /**
     * @param StoreExpenditureRequest $request
     * @return mixed
     */
    public function createExpenditure(StoreExpenditureRequest $request);

    /**
     * @param Request $request
     * @param $expenditureID
     * @return mixed
     */
    public function trashedRestore(Request $request, $expenditureID);

    /**
     * @param Expenditure $expenditure
     * @return mixed
     */
    public function getExpenditureById(Expenditure $expenditure);

    /**
     * @param UpdateExpenditureRequest $request
     * @param Expenditure $expenditure
     * @return mixed
     */
    public function updateExpenditure(UpdateExpenditureRequest $request, Expenditure $expenditure);

    /**
     * @param Request $request
     * @param Expenditure $expenditure
     * @return mixed
     */
    public function softDeleteExpenditure(Request $request, Expenditure $expenditure);

    /**
     * @param $expenditureID
     * @return mixed
     */
    public function forceDeleteExpenditure($expenditureID);
}
