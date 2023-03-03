<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StoreClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Models\Classes;
use Illuminate\Http\Request;

interface ClassesRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllClasses(Request $request);

    /**
     * @param StoreClassesRequest $request
     * @return mixed
     */
    public function createClass(StoreClassesRequest $request);

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getClassById(Classes $class);

    /**
     * @param Classes $class
     * @return mixed
     */
    public function getExamsByClassId(Classes $class);

    /**
     * @param UpdateClassesRequest $request
     * @param Classes $class
     * @return mixed
     */
    public function updateClass(UpdateClassesRequest $request, Classes $class);

    /**
     * @param Classes $class
     * @return mixed
     */
    public function forceDeleteClass(Classes $class);
}
