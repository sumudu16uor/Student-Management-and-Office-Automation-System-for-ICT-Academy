<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'fee';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'feeID' => $this->feeID,
            'studentID' => $this->studentID,
            'studentName' => $this->student->person->firstName . ' ' . $this->student->person->lastName,
            'classID' => $this->classID,
            'className' => $this->class->className,
            'paidAmount' => $this->paidAmount,
            'date' => $this->date,
            'paidStatus' => $this->paidStatus,
            'deleted_at' => $this->whenNotNull($this->deleted_at),
            'handledBy' => [
                'staffID' => $this->handlerStaffID,
                'staffName' => $this->staff->employee->title .
                    ' ' . $this->staff->employee->person->firstName . ' ' . $this->staff->employee->person->lastName,
            ],
            'branch' => [
                'branchID' => $this->branchID,
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
