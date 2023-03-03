<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'student';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'studentID' => $this->studentID,
            'firstName' => $this->person->firstName,
            'lastName' => $this->person->lastName,
            'dob' => $this->person->dob,
            'sex' => $this->person->sex,
            'grade' => $this->grade,
            'telNo' => $this->person->telNo,
            'address' => $this->person->address,
            'email' => $this->person->email,
            'status' => $this->person->status,
            'joinedDate' => $this->person->joinedDate,
            'branch' => [
                'branchID' => $this->branch->branchID,
                'branchName' => $this->branch->branchName,
            ],
            'parent' => [
                'title' => $this->parent->title,
                'parentName' => $this->parent->parentName,
                'parentType' => $this->parent->parentType,
                'parentTelNo' => $this->parent->telNo,
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
