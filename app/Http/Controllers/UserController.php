<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserLoginRecordCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in('Active', 'Deactivate'), 'string', 'max:10']
        ]);

        $users = $this->userRepository->getAllUsers($request);

        return new UserCollection($users);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserLoginRecordCollection
     */
    public function indexLoginRecords(Request $request)
    {
        $request->validate([
            'loginDate' => ['required', 'date']
        ]);

        $loginRecords = $this->userRepository->getUsersLoginRecords($request);

        return new UserLoginRecordCollection($loginRecords);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function indexStaffNotUser()
    {
        $staff = $this->userRepository->getStaffNotUser();

        return UserResource::collection($staff);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function indexTeachersNotUser()
    {
        $teachers = $this->userRepository->getTeachersNotUser();

        return UserResource::collection($teachers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $created = $this->userRepository->createUser($request);

        return new UserResource($created);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:5','max:50'],
            'password' => ['required', 'string','min:8',]
        ]);

        $user = $this->userRepository->userLogin($request);

        if (! $user || ! Hash::check($request->password, $user->getAuthPassword())) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'username' => 'The provided credentials are incorrect.'
                ]
            ], 422);
        }

        $token = $user->createToken(strtolower($user->employee->employeeType) . '-' . $user->employeeID . '-token')->plainTextToken;

        $this->userRepository->createUserLoginRecord($user);

        return new JsonResponse([
            'success' => true,
            'user' => new UserResource($user),
            '_token' => $token
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function logout(User $user)
    {
        auth()->user()->currentAccessToken()->delete();

        $logout = $this->userRepository->userLogout($user);

        return new JsonResponse([
            'success' => $logout == 1,
            'message' => 'Logged out',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function confirmPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string','min:8']
        ]);

        if (! Hash::check($request->password, $user->getAuthPassword())) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'password' => 'The provided password does not match our records.'
                ]
            ], 422);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'password confirmed',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', Rule::exists('people', 'email'), 'email', 'min:5', 'max:50'],
            'password' => ['required', 'string','size:13', 'confirmed']
        ]);

        $updated = $this->userRepository->userForgotPassword($request);

        return new JsonResponse([
            'success' => $updated == 1,
            'message' => 'Rest password'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        $user = $this->userRepository->getUserById($user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $updated = $this->userRepository->update($request, $user);

        return new UserResource($updated);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function changeUsername(UpdateUserRequest $request, User $user)
    {
        $updated = $this->userRepository->updateChangeUsername($request, $user);

        return new UserResource($updated);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function restPassword(UpdateUserRequest $request, User $user)
    {
        $updated = $this->userRepository->updateChangePassword($request, $user);

        return new JsonResponse([
            'success' => $updated,
            'status' => 'update',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $deleted = $this->userRepository->forceDeleteUser($user);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new UserResource($user),
        ]);
    }
}
