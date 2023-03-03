<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'person';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->personable->employeeType == 'Staff') {
            return [
                'personID' => $this->personID,
                'personType' => $this->personType,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'dob' => $this->dob,
                'sex' => $this->sex,
                'telNo' => $this->telNo,
                'address' => $this->address,
                'email' => $this->email,
                'status' => $this->status,
                'joinedDate' => $this->joinedDate,
                $this->personType => [
                    'employeeID' => $this->personable->employeeID,
                    'employeeType' => $this->personable->employeeType,
                    'nic' => $this->personable->nic,
                    'title' => $this->personable->title,
                    $this->personable->employeeType => [
                        'staffID' =>  $this->personable->employable->staffID,
                        'branch' => [
                            'branchID' => $this->personable->employable->branch->branchID,
                            'branchName' => $this->personable->employable->branch->branchName,
                        ],
                    ],
                ]
            ];
        }

        if ($this->personable->employeeType == 'Teacher') {
            return [
                'personID' => $this->personID,
                'personType' => $this->personType,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'dob' => $this->dob,
                'sex' => $this->sex,
                'telNo' => $this->telNo,
                'address' => $this->address,
                'email' => $this->email,
                'status' => $this->status,
                'joinedDate' => $this->joinedDate,
                $this->personType => [
                    'employeeID' => $this->personable->employeeID,
                    'employeeType' => $this->personable->employeeType,
                    'nic'=> $this->personable->nic,
                    'title'=> $this->personable->title,
                    $this->personable->employeeType => $this->personable->employable,
                ]
            ];
        }

        return  [
            'personID' => $this->personID,
            'personType' => $this->personType,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dob' => $this->dob,
            'sex' => $this->sex,
            'telNo' => $this->telNo,
            'address' => $this->address,
            'email' => $this->email,
            'status' => $this->status,
            'joinedDate' => $this->joinedDate,
            $this->personType => [
                'studentID' => $this->personable->studentID,
                'grade' => $this->personable->grade,
                'branch' => [
                    'branchID' => $this->personable->branch->branchID,
                    'branchName' => $this->personable->branch->branchName,
                ],
                'parent' => $this->personable->parent,
            ],
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
