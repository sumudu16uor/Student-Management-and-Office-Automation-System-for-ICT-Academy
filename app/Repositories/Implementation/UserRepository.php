<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Employee;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLoginRecord;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\IDGenerate\IDGenerateServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var IDGenerateServiceInterface
     */
    private IDGenerateServiceInterface $IDGenerateService;

    /**
     * @param IDGenerateServiceInterface $IDGenerateService
     */
    public function __construct(IDGenerateServiceInterface $IDGenerateService)
    {
        $this->IDGenerateService = $IDGenerateService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllUsers(Request $request)
    {
        return User::query()
            ->with(['employee.person', 'employee.employable'])
            ->where('status', data_get($request, 'status'))
            ->whereNot('privilege', 'Super')
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUsersLoginRecords(Request $request)
    {
        return UserLoginRecord::query()
            ->with([
                'user' => function ($query) {
                    $query->whereNot('privilege', 'Super');
                },
                'user.employee.person'
            ])->whereHas('user',  function (Builder $query) {
                $query->whereNot('privilege', 'Super');
            })->where('loginDate', data_get($request, 'loginDate'))
            ->get();
    }

    /**
     * @return mixed
     */
    public function getStaffNotUser()
    {
        $users = User::query()->pluck('employeeID');

        return Staff::query()
            ->with([
                'employee.person' => function ($query) {
                    $query->whereNotIn('status', ['Super', 'Deactivate']);
                },
                'employee.employable.branch'
            ])->whereHas('employee.person', function (Builder $query) {
                $query->whereNotIn('status', ['Super', 'Deactivate']);
            })->whereNotIn('staffID', $users)
            ->get();
    }

    /**
     * @return mixed
     */
    public function getTeachersNotUser()
    {
        $users = User::query()->pluck('employeeID');

        return Teacher::query()
            ->with(['employee.person' => function ($query) {
                $query->whereNotIn('status', ['Super', 'Deactivate']);
            }])->whereHas('employee.person', function (Builder $query) {
                $query->whereNotIn('status', ['Super', 'Deactivate']);
            })->whereNotIn('teacherID', $users)
            ->get();
    }

    /**
     * @param StoreUserRequest $request
     * @return mixed
     */
    public function createUser(StoreUserRequest $request)
    {
        return User::query()->create([
            'userID' => $this->IDGenerateService->userID(),
            'username' => data_get($request, 'email'),
            'password' => Hash::make(data_get($request,'password')),
            'privilege' => data_get($request, 'privilege'),
            'employeeID' => data_get($request, 'employeeID'),
            'status' => data_get($request, 'status'),
        ]);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function createUserLoginRecord(User $user)
    {
        $user = User::query()->find(data_get($user, 'userID'));

        return $user->userLoginRecords()->create([
            'loginDate' => Carbon::now()->format('Y-m-d'),
            'loginTime' => Carbon::now()->format('h:i:s'),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function userLogin(Request $request)
    {
        return User::query()->with(['employee.person', 'employee.employable'])
            ->where('username', $request->username)
            ->whereNot('status', 'Deactivate')
            ->first();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function userLogout(User $user)
    {
        $user = User::query()->find(data_get($user, 'userID'));

        return $user->userLoginRecords()
            ->orderBy('user_login_records.loginDate', 'desc')
            ->orderBy('user_login_records.loginTime', 'desc')
            ->take(1)
            ->update([
                'logoutTime' => Carbon::now()->format('h:i:s'),
            ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function userForgotPassword(Request $request)
    {
        $employee = Employee::query()
            ->whereHas('person', function (Builder $query) use ($request) {
                $query->where('email', $request->email);
            })->first();

        $updated = $employee->user()->update([
            'password' => Hash::make(data_get($request,'password'))
        ]);

        if (!$updated) {
            throw new Exception('Failed to update password:' . $request->email);
        }

        return $updated;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserById(User $user)
    {
        return User::query()->find($user->userID);
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $updated = $user->update([
            'privilege' => data_get($request, 'privilege', $user->privilege),
            'status' => data_get($request, 'status', $user->status)
        ]);

        if (!$updated) {
            throw new Exception('Failed to update User: ' . $user->userID);
        }

        return $user;
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    public function updateChangeUsername(UpdateUserRequest $request, User $user)
    {
        $updated = $user->update([
            'username' => data_get($request, 'username', $user->username)
        ]);

        if (!$updated) {
            throw new Exception('Failed to update username of User: ' . $user->userID);
        }

        return $user;
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    public function updateChangePassword(UpdateUserRequest $request, User $user)
    {
        $updated = $user->update([
            'password' => Hash::make(data_get($request,'password'))
        ]);

        if (!$updated) {
            throw new Exception('Failed to update password of User: ' . $user->userID);
        }

        return $updated;
    }

    /**
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteUser(User $user)
    {
        $deleted = $user->delete();

        if (!$deleted){
            throw new Exception('Failed to delete User: ' . $user->userID);
        }

        return $deleted;
    }
}
