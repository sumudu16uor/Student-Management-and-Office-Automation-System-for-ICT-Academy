<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreExpenditureRequest;
use App\Http\Requests\UpdateExpenditureRequest;
use App\Models\Expenditure;
use App\Repositories\Interfaces\ExpenditureRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenditureRepository implements ExpenditureRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllExpenditures(Request $request)
    {
        if ($request->date != null) {
            return Expenditure::query()->with(['staff', 'branch'])
                ->whereYear('date', data_get($request, 'date'))
                ->whereMonth('date', Carbon::make(data_get($request, 'date'))->format('m'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Expenditure::query()->with(['staff', 'branch'])
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllTrashedExpenditures(Request $request)
    {
        if ($request->date != null) {
            return Expenditure::onlyTrashed()
                ->with(['staff', 'branch'])
                ->whereYear('date', data_get($request, 'date'))
                ->whereMonth('date', Carbon::make(data_get($request, 'date'))->format('m'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Expenditure::onlyTrashed()
            ->with(['staff', 'branch'])
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * @param StoreExpenditureRequest $request
     * @return mixed
     */
    public function createExpenditure(StoreExpenditureRequest $request)
    {
        return Expenditure::query()->create([
            'expense' => data_get($request, 'expense'),
            'expenseAmount' => data_get($request, 'expenseAmount'),
            'date' => data_get($request, 'date'),
            'handlerStaffID' => data_get($request, 'handlerStaffID'),
            'branchID' => data_get($request, 'branchID'),
        ]);
    }

    /**
     * @param Request $request
     * @param $expenditureID
     * @return mixed
     */
    public function trashedRestore(Request $request, $expenditureID)
    {
        $expenditure = Expenditure::withTrashed()->findOrFail($expenditureID);

        if ($expenditure->trashed()) {
            $expenditure->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);
        }

        return $expenditure->trashed() ? $expenditure->restore() : false;
    }

    /**
     * @param Expenditure $expenditure
     * @return mixed
     */
    public function getExpenditureById(Expenditure $expenditure)
    {
        return Expenditure::query()->find($expenditure);
    }

    /**
     * @param UpdateExpenditureRequest $request
     * @param Expenditure $expenditure
     * @return mixed
     * @throws Exception
     */
    public function updateExpenditure(UpdateExpenditureRequest $request, Expenditure $expenditure)
    {
        $updated = $expenditure->update([
            'expense' => data_get($request, 'expense', $expenditure->expense),
            'expenseAmount' => data_get($request, 'expenseAmount', $expenditure->expenseAmount),
            'date' => data_get($request, 'date', $expenditure->date),
            'handlerStaffID' => data_get($request, 'handlerStaffID', $expenditure->handlerStaffID),
            'branchID' => data_get($request, 'branchID', $expenditure->branchID),
        ]);

        if (!$updated) {
            throw new Exception('Failed to update Expenditures: ' . $expenditure->expenseID);
        }

        return $expenditure;
    }

    /**
     * @param Request $request
     * @param Expenditure $expenditure
     * @return mixed
     * @throws Exception
     */
    public function softDeleteExpenditure(Request $request, Expenditure $expenditure)
    {
        $expenditure->update([
            'handlerStaffID' => data_get($request, 'handlerStaffID'),
        ]);

        $deleted = $expenditure->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Expenditures: ' . $expenditure->expenseID);
        }

        return $deleted;
    }

    /**
     * @param $expenditureID
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteExpenditure($expenditureID)
    {
        $expenditure = Expenditure::withTrashed()->findOrFail($expenditureID);

        return $expenditure->trashed() ? $expenditure->forceDelete() : false;
    }
}
