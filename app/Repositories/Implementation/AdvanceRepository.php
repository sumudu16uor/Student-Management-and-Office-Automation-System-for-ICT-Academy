<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreAdvanceRequest;
use App\Http\Requests\UpdateAdvanceRequest;
use App\Models\Advance;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdvanceRepository implements AdvanceRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllAdvances(Request $request)
    {
        if ($request->date != null) {
            return Advance::query()->with(['employee', 'staff', 'branch'])
                ->join('employees', 'advances.employeeID' , 'employees.employeeID')
                ->where('employees.employeeType', data_get($request, 'employeeType'))
                ->whereYear('date', data_get($request, 'date'))
                ->whereMonth('date', Carbon::make(data_get($request, 'date'))->format('m'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Advance::query()->with(['employee', 'staff', 'branch'])
            ->join('employees', 'advances.employeeID' , 'employees.employeeID')
            ->where('employees.employeeType', data_get($request, 'employeeType'))
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
    public function getAllTrashedAdvances(Request $request)
    {
        if ($request->date != null) {
            return Advance::onlyTrashed()
                ->with(['employee', 'staff', 'branch'])
                ->join('employees', 'advances.employeeID' , 'employees.employeeID')
                ->where('employees.employeeType', data_get($request, 'employeeType'))
                ->whereYear('date', data_get($request, 'date'))
                ->whereMonth('date', Carbon::make(data_get($request, 'date'))->format('m'))
                ->orderBy('date', 'asc')
                ->get();
        }

        return Advance::onlyTrashed()
            ->with(['employee', 'staff', 'branch'])
            ->join('employees', 'advances.employeeID' , 'employees.employeeID')
            ->where('employees.employeeType', data_get($request, 'employeeType'))
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * @param StoreAdvanceRequest $request
     * @return mixed
     */
    public function createAdvance(StoreAdvanceRequest $request)
    {
        return Advance::query()->create([
            'description' => data_get($request, 'description'),
            'advanceAmount' => data_get($request, 'advanceAmount'),
            'date' => data_get($request, 'date'),
            'employeeID' => data_get($request, 'employeeID'),
            'handlerStaffID' => data_get($request, 'handlerStaffID'),
            'branchID' => data_get($request, 'branchID'),
        ]);
    }

    /**
     * @param Request $request
     * @param $advanceID
     * @return mixed
     */
    public function trashedRestore(Request $request, $advanceID)
    {
        $advance = Advance::withTrashed()->findOrFail($advanceID);

        if ($advance->trashed()) {
            $advance->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);
        }

        return $advance->trashed() ? $advance->restore() : false;
    }

    /**
     * @param Advance $advance
     * @return mixed
     */
    public function getAdvanceById(Advance $advance)
    {
        return Advance::query()->find($advance);
    }

    /**
     * @param UpdateAdvanceRequest $request
     * @param Advance $advance
     * @return mixed
     * @throws Exception
     */
    public function updateAdvance(UpdateAdvanceRequest $request, Advance $advance)
    {
        $updated = $advance->update([
            'description' => data_get($request, 'description', $advance->description),
            'advanceAmount' => data_get($request, 'advanceAmount', $advance->advanceAmount),
            'date' => data_get($request, 'date', $advance->date),
            'employeeID' => data_get($request, 'employeeID', $advance->employeeID),
            'handlerStaffID' => data_get($request, 'handlerStaffID', $advance->handlerStaffID),
            'branchID' => data_get($request, 'branchID', $advance->branchID),
        ]);

        if (!$updated) {
            throw new Exception('Failed to update Advance: ' . $advance->advanceID);
        }

        return $advance;
    }

    /**
     * @param Request $request
     * @param Advance $advance
     * @return mixed
     * @throws Exception
     */
    public function softDeleteAdvance(Request $request, Advance $advance)
    {
        $advance->update([
            'handlerStaffID' => data_get($request, 'handlerStaffID'),
        ]);

        $deleted = $advance->delete();

        if (!$deleted){
            throw new Exception('Failed to soft delete advance: ' . $advance->advanceID);
        }

        return $deleted;
    }

    /**
     * @param $advanceID
     * @return mixed
     */
    public function forceDeleteAdvance($advanceID)
    {
        $advance = Advance::withTrashed()->findOrFail($advanceID);

        return $advance->trashed() ? $advance->forceDelete() : false;
    }
}
