<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvanceResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'advance';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->employee->employeeType == 'Teacher') {
            return [
                'advanceID' => $this->advanceID,
                'description' => $this->description,
                'advanceAmount' => $this->advanceAmount,
                'date' => $this->date,
                'deleted_at' => $this->whenNotNull($this->deleted_at),
                'teacher' =>  [
                    'teacherID' => $this->employee->employeeID,
                    'teacherName' => $this->employee->title .
                        ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                ],
                'handledBy' => [
                    'staffID' => $this->staff->staffID,
                    'staffName' => $this->staff->employee->title .
                        ' ' . $this->staff->employee->person->firstName . ' ' . $this->staff->employee->person->lastName,
                ],
                'branch' => [
                    'branchID' => $this->branch->branchID,
                    'branchName' => $this->branch->branchName,
                ],
            ];
        }

        return [
            'advanceID' => $this->advanceID,
            'description' => $this->description,
            'advanceAmount' => $this->advanceAmount,
            'date' => $this->date,
            'deleted_at' => $this->whenNotNull($this->deleted_at),
            'staff' =>  [
                'staffID' => $this->employee->employeeID,
                'staffName' => $this->employee->title .
                    ' ' . $this->employee->person->firstName . ' ' . $this->employee->person->lastName,
                'branch' => [
                    'branchID' => $this->employee->employable->branch->branchID,
                    'branchName' => $this->employee->employable->branch->branchName,
                ],
            ],
            'handledBy' => [
                'staffID' => $this->staff->staffID,
                'staffName' => $this->staff->employee->title .
                    ' ' . $this->staff->employee->person->firstName . ' ' . $this->staff->employee->person->lastName,
            ],
            'branch' => [
                'branchID' => $this->branch->branchID,
                'branchName' => $this->branch->branchName,
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
