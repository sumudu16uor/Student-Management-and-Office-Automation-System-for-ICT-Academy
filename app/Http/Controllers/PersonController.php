<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonCollection;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    /**
     * @var PersonRepositoryInterface
     */
    private PersonRepositoryInterface $personRepository;

    /**
     * @param PersonRepositoryInterface $personRepository
     */
    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return PersonCollection
     */
    public function index(Request $request)
    {
        $request->validate(['status' => ['required', 'string', 'max:10',
            Rule::in(['Active', 'Past', 'Deactivate'])]]);

        $people = $this->personRepository->getAllPeople($request);

        return new PersonCollection($people);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePersonRequest $request
     * @return PersonResource
     */
    public function store(StorePersonRequest $request)
    {
        $created = $this->personRepository->createPerson($request);

        return new PersonResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param Person $person
     * @return PersonCollection
     */
    public function show(Person $person)
    {
        $person = $this->personRepository->getPersonById($person);

        return new PersonCollection($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePersonRequest $request
     * @param Person $person
     * @return PersonResource
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        $updated = $this->personRepository->updatePerson($request, $person);

        return new PersonResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Person $person
     * @return JsonResponse
     */
    public function destroy(Person $person)
    {
        $deleted = $this->personRepository->forceDeletePerson($person);

        return new JsonResponse([
            'success' => $deleted,
            'status' => 'deleted',
            'data' => new PersonResource($person),
        ]);
    }
}
