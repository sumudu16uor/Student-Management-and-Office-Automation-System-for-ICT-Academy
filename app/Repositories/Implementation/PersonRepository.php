<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Employee;
use App\Models\Parents;
use App\Models\Person;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class PersonRepository implements PersonRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getAllPeople(Request $request)
    {
        return Person::query()->with(['personable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Employee::class => ['employable'],
                    Parents::class => ['parent']
                ]);
            }])->where('people.status', data_get($request, 'status'))->get();
    }

    /**
     * @param StorePersonRequest $request
     * @return mixed
     * @throws Exception
     */
    public function createPerson(StorePersonRequest $request)
    {
        throw new Exception('The HTTP request cannot be handled by the server.Not Implemented and Under Construction');
    }

    /**
     * @param Person $person
     * @return mixed
     */
    public function getPersonById(Person $person)
    {
        return Person::query()->find($person);
    }

    /**
     * @param UpdatePersonRequest $request
     * @param Person $person
     * @return mixed
     * @throws Exception
     */
    public function updatePerson(UpdatePersonRequest $request, Person $person)
    {
        $updated = $person->update([
            'firstName' => data_get($request, 'firstName', $person->firstName),
            'lastName' => data_get($request, 'lastName', $person->lastName),
            'dob' => data_get($request, 'dob', $person->dob),
            'sex' => data_get($request, 'sex', $person->sex),
            'telNo' => data_get($request, 'telNo', $person->telNo),
            'address' => data_get($request, 'address', $person->address),
            'email' => data_get($request, 'email', $person->email),
            'status' => data_get($request, 'status', $person->status),
            'joinedDate' => data_get($request, 'joinedDate', $person->joinedDate),
        ]);

        if (!$updated){
            throw new Exception('Failed to update Person: ' . $person->personID);
        }

        return $person;
    }

    /**
     * @param Person $person
     * @return mixed
     * @throws Exception
     */
    public function forceDeletePerson(Person $person)
    {
        $deleted = $person->delete();

        if (!$deleted){
            throw new Exception('Failed to delete Person: ' . $person->personID);
        }

        return $deleted;
    }
}
