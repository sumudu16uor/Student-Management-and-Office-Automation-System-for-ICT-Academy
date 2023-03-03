<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentsCollection;
use App\Http\Resources\ParentsResource;
use App\Models\Parents;
use App\Http\Requests\StoreParentsRequest;
use App\Http\Requests\UpdateParentsRequest;
use App\Repositories\Interfaces\ParentsRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ParentsController extends Controller
{
    /**
     * @var ParentsRepositoryInterface
     */
    private ParentsRepositoryInterface $parentsRepository;

    /**
     * @param ParentsRepositoryInterface $parentsRepository
     */
    public function __construct(ParentsRepositoryInterface $parentsRepository)
    {
        $this->parentsRepository = $parentsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ParentsCollection
     */
    public function index()
    {
        $parents = $this->parentsRepository->getAllParents();

        return new ParentsCollection($parents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreParentsRequest $request
     * @return ParentsResource
     */
    public function store(StoreParentsRequest $request)
    {
        $created = $this->parentsRepository->createParent($request);

        return new ParentsResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Parents $parent
     * @return ParentsCollection
     */
    public function show(Parents $parent)
    {
        $parent = $this->parentsRepository->getParentById($parent);

        return new ParentsCollection($parent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateParentsRequest $request
     * @param Parents $parent
     * @return ParentsResource
     */
    public function update(UpdateParentsRequest $request, Parents $parent)
    {
        $updated = $this->parentsRepository->updateParent($request, $parent);

        return new ParentsResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Parents $parent
     * @return JsonResponse
     */
    public function destroy(Parents $parent)
    {
        $deleted = $this->parentsRepository->forceDeleteParent($parent);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new ParentsResource($parent),
        ]);
    }
}
