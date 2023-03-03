<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllUsers(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUsersLoginRecords(Request $request);

    /**
     * @return mixed
     */
    public function getStaffNotUser();

    /**
     * @return mixed
     */
    public function getTeachersNotUser();

    /**
     * @param StoreUserRequest $request
     * @return mixed
     */
    public function createUser(StoreUserRequest $request);

    /**
     * @param User $user
     * @return mixed
     */
    public function createUserLoginRecord(User $user);

    /**
     * @param Request $request
     * @return mixed
     */
    public function userLogin(Request $request);

    /**
     * @param User $user
     * @return mixed
     */
    public function userLogout(User $user);

    /**
     * @param Request $request
     * @return mixed
     */
    public function userForgotPassword(Request $request);

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserById(User $user);

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user);

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     */
    public function updateChangeUsername(UpdateUserRequest $request, User $user);

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return mixed
     */
    public function updateChangePassword(UpdateUserRequest $request, User $user);

    /**
     * @param User $user
     * @return mixed
     */
    public function forceDeleteUser(User $user);
}
