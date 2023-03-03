<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'employee';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->employeeType == 'Staff') {
            return [
                'personID' => $this->person->personID,
                'personType' => $this->person->personType,
                'firstName' => $this->person->firstName,
                'lastName' => $this->person->lastName,
                'dob' => $this->person->dob,
                'sex' => $this->person->sex,
                'telNo' => $this->person->telNo,
                'address' => $this->person->address,
                'email' => $this->person->email,
                'status' => $this->person->status,
                'joinedDate' => $this->person->joinedDate,
                'employee' => [
                    'employeeID' => $this->employeeID,
                    'employeeType' => $this->employeeType,
                    'nic' => $this->nic,
                    'title' => $this->title,
                    'Staff' => [
                        'staffID' => $this->employable->staffID,
                        'branch' => [
                            'branchID' => $this->employable->branch->branchID,
                            'branchName' => $this->employable->branch->branchName,
                        ],
                    ],
                ]
            ];
        }

        return [
            'personID' => $this->person->personID,
            'personType' => $this->person->personType,
            'firstName' => $this->person->firstName,
            'lastName' => $this->person->lastName,
            'dob' => $this->person->dob,
            'sex' => $this->person->sex,
            'telNo' => $this->person->telNo,
            'address' => $this->person->address,
            'email' => $this->person->email,
            'status' => $this->person->status,
            'joinedDate' => $this->person->joinedDate,
            'employee' => [
                'employeeID' => $this->employeeID,
                'employeeType' => $this->employeeType,
                'nic' => $this->nic,
                'title' => $this->title,
                $this->employeeType => $this->employable,
            ]
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
