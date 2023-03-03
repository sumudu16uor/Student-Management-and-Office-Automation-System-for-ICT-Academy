<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreParentsRequest;
use App\Http\Requests\UpdateParentsRequest;
use App\Models\Parents;
use App\Repositories\Interfaces\ParentsRepositoryInterface;
use Exception;

class ParentsRepository implements ParentsRepositoryInterface
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllParents()
    {
        return Parents::query()->get();
    }

    /**
     * @param StoreParentsRequest $request
     * @return mixed
     */
    public function createParent(StoreParentsRequest $request)
    {
        return Parents::query()->create([
            'studentID' => data_get($request, 'studentID'),
            'title' => data_get($request, 'title'),
            'parentName' => data_get($request, 'parentName'),
            'parentType' =>  data_get($request, 'parentType'),
            'telNo' => data_get($request, 'telNo'),
        ]);
    }

    /**
     * @param Parents $parent
     * @return mixed
     */
    public function getParentById(Parents $parent)
    {
        return Parents::query()->find($parent);
    }

    /**
     * @param UpdateParentsRequest $request
     * @param Parents $parent
     * @return mixed
     * @throws Exception
     */
    public function updateParent(UpdateParentsRequest $request, Parents $parent)
    {
        $updated = $parent->update([
            'title' => data_get($request, 'title', $parent->title),
            'parentName' => data_get($request, 'parentName',  $parent->parentName),
            'parentType' =>  data_get($request, 'parentType' , $parent->parentType),
            'telNo' => data_get($request, 'telNo', $parent->telNo),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Category: ' . $parent->studentID);
        }

        return $parent;
    }

    /**
     * @param Parents $parent
     * @return mixed
     * @throws Exception
     */
    public function forceDeleteParent(Parents $parent)
    {
        $deleted = $parent->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Category: ' . $parent->studentID);
        }

        return $deleted;
    }
}
