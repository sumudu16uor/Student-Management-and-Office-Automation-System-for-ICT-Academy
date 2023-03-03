<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Person;
use App\Models\Process;
use App\Models\Student;
use App\Models\UserLoginRecord;
use App\Repositories\Interfaces\ProcessRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessRepository implements ProcessRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllProcesses(Request $request)
    {
        if ($request->date != null) {
            return Process::withTrashed()
                ->with(['staff.employee.person', 'branch'])
                ->whereYear('updated_at', data_get($request, 'date'))
                ->get();
        }

        return Process::withTrashed()
            ->with(['staff.employee.person', 'branch'])
            ->whereYear('updated_at', Carbon::now()->year)
            ->get();
    }

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function monthEndProcess(StoreProcessRequest $request)
    {
        $classes = Classes::query()->whereHas('students')
            ->where('feeType', 'Monthly')
            ->where('status', 'Active')
            ->get();

        return DB::transaction(function () use ($request, $classes) {

            foreach ($classes as $class) {
                $class->students()
                    ->where('enrollment.status', '1')
                    ->whereNot('enrollment.paymentStatus', '-1')
                    ->increment('paymentStatus');
            }

            return Process::query()->create([
                'processType' => 'month_end',
                'updated_at' => Carbon::now(),
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
                'branchID' => data_get($request, 'branchID'),
            ]);
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function yearEndProcess(StoreProcessRequest $request)
    {
        return DB::transaction(function () use ($request) {

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Past');
                })->where('grade', '11')
                ->increment('grade');

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Active');
                })->whereNotIn('grade', ['11', '12', '13', 'Other'])
                ->increment('grade');

            Classes::query()->where('status', 'Active')
                ->whereNotIn('grade', ['11', '12', '13', 'Other'])
                ->increment('grade');

            return Process::query()->create([
                'processType' => 'year_end',
                'updated_at' => Carbon::now(),
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
                'branchID' => data_get($request, 'branchID'),
            ]);
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function ordinaryLevelEndProcess(StoreProcessRequest $request)
    {
        $students = Student::query()->where('grade', '11')->pluck('studentID');

        $classes = Classes::query()->where('grade', '11')->pluck('classID');

        return DB::transaction(function () use ($request, $students, $classes) {

            Person::query()->whereIn('personID', $students)->update([
                'status' => 'Past'
            ]);

            Enrollment::query()->whereIn('studentID', $students)->update([
                'status' => '0'
            ]);

            Classes::query()->whereIn('classID', $classes)->update([
                'status' => 'Deactivate'
            ]);

            Enrollment::query()->whereIn('classID', $classes)->update([
                'status' => '0'
            ]);

            return Process::query()->create([
                'processType' => 'ol_batch_end',
                'updated_at' => Carbon::now(),
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
                'branchID' => data_get($request, 'branchID'),
            ]);
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function advancedLevelEndProcess(StoreProcessRequest $request)
    {
        $students = Student::query()
            ->whereHas('person', function (Builder $query) {
                $query->where('status', 'Active');
            })->where('grade', '13')
            ->pluck('studentID');

        $classes = Classes::query()
            ->where('status', 'Active')
            ->where('grade', '13')
            ->pluck('classID');

        return DB::transaction(function () use ($request, $students, $classes) {

            Person::query()->whereIn('personID', $students)->update([
                'status' => 'Past'
            ]);

            Enrollment::query()->whereIn('studentID', $students)->update([
                'status' => '0'
            ]);

            Classes::query()->whereIn('classID', $classes)->update([
                'status' => 'Deactivate'
            ]);

            Enrollment::query()->whereIn('classID', $classes)->update([
                'status' => '0'
            ]);

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Active');
                })->where('grade', '12')
                ->increment('grade');

            Classes::query()->where('status', 'Active')
                ->where('grade', '12')
                ->increment('grade');

            return Process::query()->create([
                'processType' => 'al_batch_end',
                'updated_at' => Carbon::now(),
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
                'branchID' => data_get($request, 'branchID'),
            ]);
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function clearLoginHistory(StoreProcessRequest $request)
    {
        return DB::transaction(function () use ($request) {

            UserLoginRecord::query()
                ->where('loginDate', '<', Carbon::now()->subMonth(1)->format('Y-m-d'))
                ->delete();

            return Process::query()->create([
                'processType' => 'clear_login',
                'updated_at' => Carbon::now(),
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
                'branchID' => data_get($request, 'branchID'),
            ]);
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     * @throws Throwable
     */
    public function reverseMonthEndProcess(StoreProcessRequest $request, Process $process)
    {
        $classes = Classes::query()->whereHas('students')
            ->where('feeType', 'Monthly')
            ->where('status', 'Active')
            ->get();

        return DB::transaction(function () use ($request, $process, $classes) {

            foreach ($classes as $class) {
                $class->students()
                    ->where('enrollment.status', '1')
                    ->whereNotIn('enrollment.paymentStatus', ['-1', '0'])
                    ->decrement('paymentStatus');
            }

            $process->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);

            return $process->delete();
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     * @throws Throwable
     */
    public function reverseYearEndProcess(StoreProcessRequest $request, Process $process)
    {
        return DB::transaction(function () use ($request, $process) {

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Past');
                })->where('grade', '12')
                ->decrement('grade');

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Active');
                })->whereNotIn('grade', ['12', '13', 'Other'])
                ->decrement('grade');

            Classes::query()->where('status', 'Active')
                ->whereNotIn('grade', ['12', '13', 'Other'])
                ->decrement('grade');

            $process->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);

            return $process->delete();
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     * @throws Throwable
     */
    public function reverseOrdinaryLevelEndProcess(StoreProcessRequest $request, Process $process)
    {
        $students = Student::query()
            ->whereHas('person', function (Builder $query) {
                $query->where('status', 'Past');
            })->where('grade', '11')
            ->pluck('studentID');

        $classes = Classes::query()
            ->where('status', 'Deactivate')
            ->where('grade', '11')
            ->pluck('classID');

        return DB::transaction(function () use ($request, $process, $students, $classes) {

            Person::query()->whereIn('personID', $students)->update([
                'status' => 'Active'
            ]);

            Enrollment::query()->whereIn('studentID', $students)->update([
                'status' => '1'
            ]);

            Classes::query()->whereIn('classID', $classes)->update([
                'status' => 'Active'
            ]);

            Enrollment::query()->whereIn('classID', $classes)->update([
                'status' => '1'
            ]);

            $process->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);

            return $process->delete();
        });
    }

    /**
     * @param StoreProcessRequest $request
     * @param Process $process
     * @return mixed
     * @throws Throwable
     */
    public function reverseAdvancedLevelEndProcess(StoreProcessRequest $request, Process $process)
    {
        $students = Student::query()
            ->whereHas('person', function (Builder $query) {
                $query->where('status', 'Past');
            })->where('grade', '13')
            ->pluck('studentID');

        $classes = Classes::query()
            ->where('status', 'Deactivate')
            ->where('grade', '13')
            ->pluck('classID');

        return DB::transaction(function () use ($request, $process, $students, $classes) {

            Student::query()
                ->whereHas('person', function (Builder $query) {
                    $query->where('status', 'Active');
                })->where('grade', '13')
                ->decrement('grade');

            Classes::query()->where('status', 'Active')
                ->where('grade', '13')
                ->decrement('grade');

            Person::query()->whereIn('personID', $students)->update([
                'status' => 'Active'
            ]);

            Enrollment::query()->whereIn('studentID', $students)->update([
                'status' => '1'
            ]);

            Classes::query()->whereIn('classID', $classes)->update([
                'status' => 'Active'
            ]);

            Enrollment::query()->whereIn('classID', $classes)->update([
                'status' => '1'
            ]);

            $process->update([
                'handlerStaffID' => data_get($request, 'handlerStaffID'),
            ]);

            return $process->delete();
        });
    }

    /**
     * @param Process $process
     * @return mixed
     */
    public function getProcessById(Process $process)
    {
        return Process::query()->with(['staff.employee.person', 'branch'])->find($process->processID);
    }

    /**
     * @return mixed
     */
    public function getMonthEndProcess()
    {
        return Process::withoutTrashed()->with(['staff.employee.person', 'branch'])
            ->where('processType', 'month_end')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->latest('updated_at')
            ->first();
    }

    /**
     * @return mixed
     */
    public function getYearEndProcess()
    {
        return Process::withoutTrashed()->with(['staff.employee.person', 'branch'])
            ->where('processType', 'year_end')
            ->whereYear('updated_at', Carbon::now()->year)
            ->latest('updated_at')
            ->first();
    }

    /**
     * @return mixed
     */
    public function getOrdinaryLevelEndProcess()
    {
        return Process::withoutTrashed()->with(['staff.employee.person', 'branch'])
            ->where('processType', 'ol_batch_end')
            ->whereYear('updated_at', Carbon::now()->year)
            ->latest('updated_at')
            ->first();
    }

    /**
     * @return mixed
     */
    public function getAdvancedLevelEndProcess()
    {
        return Process::withoutTrashed()->with(['staff.employee.person', 'branch'])
            ->where('processType', 'al_batch_end')
            ->where('updated_at', '>', Carbon::now()->subYear()->addDay()->format('Y-m-d'))
            ->latest('updated_at')
            ->first();
    }

    /**
     * @return mixed
     */
    public function getClearLogin()
    {
        return Process::withoutTrashed()->with(['staff.employee.person', 'branch'])
            ->where('processType', 'clear_login')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->latest('updated_at')
            ->first();
    }

    /**
     * @param UpdateProcessRequest $request
     * @param Process $process
     * @return mixed
     * @throws Exception
     */
    public function updateProcess(UpdateProcessRequest $request, Process $process)
    {
        $updated = $process->update([
            'processType' => data_get($request, 'processType', $process->processType),
            'updated_at' => Carbon::now(),
            'handlerStaffID' => data_get($request, 'handlerStaffID', $process->handlerStaffID),
            'branchID' => data_get($request, 'branchID', $process->branchID),
        ]);

        if (!$updated) {
            throw new Exception('Failed to update Process: ' . $process->processID);
        }

        return $process;
    }

    /**
     * @param $processID
     * @return mixed
     */
    public function forceDeleteProcess($processID)
    {
        $process = Process::withTrashed()->findOrFail($processID);

        return $process->trashed() ? $process->forceDelete() : false;
    }
}
