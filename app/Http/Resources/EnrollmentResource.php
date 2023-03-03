<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'enrollment';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->classID != null) {
            return [
                'classID' => $this->classID,
                'className' => $this->className,
                'day' => $this->day,
                'grade' => $this->grade,
                'feeType' => $this->feeType,
                'status' => $this->status,
                'subject' => [
                    'subjectID' => $this->subject->subjectID,
                    'subjectName' =>  $this->subject->subjectName,
                    'medium' =>  $this->subject->medium,
                ],
                'category' => $this->category,
                'teacher' => [
                    'teacherID' => $this->teacher->teacherID,
                    'teacherName' => $this->teacher->employee->title .
                        ' ' . $this->teacher->employee->person->firstName . ' ' . $this->teacher->employee->person->lastName,
                ],
                'branch' => [
                    'branchID' => $this->branch->branchID,
                    'branchName' => $this->branch->branchName,
                ],
            ];
        }

        return [
            'studentID' => $this->studentID,
            'studentName' => $this->person->firstName. ' ' . $this->person->lastName,
            'grade' => $this->grade,
            'status' => $this->person->status,
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
