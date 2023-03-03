<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->employee->employeeType == 'Staff' && $this->userID == null) {
            return [
                'staffID' => $this->staffID,
                'name' => $this->employee->title . ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                'employeeType' => $this->employee->employeeType,
                'status' => $this->employee->person->status,
                'email' => $this->employee->person->email,
                'branch' => [
                    'branchID' => $this->branch->branchID,
                    'branchName' => $this->branch->branchName,
                ],
            ];
        }

        if ($this->employee->employeeType == 'Teacher' && $this->userID == null) {
            return [
                'teacherID' => $this->teacherID,
                'name' => $this->employee->title . ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                'employeeType' => $this->employee->employeeType,
                'status' => $this->employee->person->status,
                'email' => $this->employee->person->email,
            ];
        }

        if ($this->employee->employeeType == 'Teacher' && $this->userID != null) {
            return [
                'userID' => $this->userID,
                'username' => $this->username,
                'privilege' => $this->privilege,
                'status' => $this->status,
                'employee' => [
                    'employeeID' => $this->employeeID,
                    'name' => $this->employee->title . ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                    'employeeType' => $this->employee->employeeType,
                    'email' => $this->employee->person->email,
                    'branch' => [
                        'branchID' => null,
                        'branchName' => null,
                    ],
                ],
            ];
        }

        return [
            'userID' => $this->userID,
            'username' => $this->username,
            'privilege' => $this->privilege,
            'status' => $this->status,
            'employee' => [
                'employeeID' => $this->employeeID,
                'name' => $this->employee->title . ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                'employeeType' => $this->employee->employeeType,
                'email' => $this->employee->person->email,
                'branch' => [
                    'branchID' => $this->employee->employable->branch->branchID,
                    'branchName' => $this->employee->employable->branch->branchName,
                ],
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
