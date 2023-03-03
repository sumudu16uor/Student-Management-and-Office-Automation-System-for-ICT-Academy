<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffCollection;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * @var StaffRepositoryInterface
     */
    private StaffRepositoryInterface $staffRepository;

    /**
     * @param StaffRepositoryInterface $staffRepository
     */
    public function __construct(StaffRepositoryInterface $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return StaffCollection
     */
    public function index(Request $request)
    {
        $request->validate(['status' => ['required', 'string', 'max:10',
            Rule::in(['Active', 'Deactivate'])]]);

        $staffs = $this->staffRepository->getAllStaffs($request);

        return new StaffCollection($staffs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStaffRequest $request
     * @return StaffResource
     */
    public function store(StoreStaffRequest $request)
    {
        $created = $this->staffRepository->createStaff($request);

        DB::statement('SET foreign_key_checks = 1;');

        return new StaffResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Staff $staff
     * @return StaffCollection
     */
    public function show(Staff $staff)
    {
        $staff = $this->staffRepository->getStaffById($staff);

        return new StaffCollection($staff);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStaffRequest $request
     * @param Staff $staff
     * @return StaffResource
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $updated = $this->staffRepository->updateStaff($request, $staff);

        return new StaffResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Staff $staff
     * @return JsonResponse
     */
    public function destroy(Staff $staff)
    {
        $deleted = $this->staffRepository->forceDeleteStaff($staff);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new StaffResource($staff),
        ]);
    }
}
