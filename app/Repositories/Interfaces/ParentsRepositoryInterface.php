<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreParentsRequest;
use App\Http\Requests\UpdateParentsRequest;
use App\Models\Parents;

interface ParentsRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllParents();

    /**
     * @param StoreParentsRequest $request
     * @return mixed
     */
    public function createParent(StoreParentsRequest $request);

    /**
     * @param Parents $parent
     * @return mixed
     */
    public function getParentById(Parents $parent);

    /**
     * @param UpdateParentsRequest $request
     * @param Parents $parent
     * @return mixed
     */
    public function updateParent(UpdateParentsRequest $request, Parents $parent);

    /**
     * @param Parents $parent
     * @return mixed
     */
    public function forceDeleteParent(Parents $parent);
}
