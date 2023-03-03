<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use Illuminate\Http\Request;

interface PersonRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllPeople(Request $request);

    /**
     * @param StorePersonRequest $request
     * @return mixed
     */
    public function createPerson(StorePersonRequest $request);

    /**
     * @param Person $person
     * @return mixed
     */
    public function getPersonById(Person $person);

    /**
     * @param UpdatePersonRequest $request
     * @param Person $person
     * @return mixed
     */
    public function updatePerson(UpdatePersonRequest $request, Person $person);

    /**
     * @param Person $person
     * @return mixed
     */
    public function forceDeletePerson(Person $person);
}
