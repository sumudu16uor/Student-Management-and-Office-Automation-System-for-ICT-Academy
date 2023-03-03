<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassesResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'class';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->routeIs('teachers.show.classesWithExam')) {
            return [
                'classID' => $this->classID,
                'className' => $this->className,
                'day' => $this->day,
                'startTime' => date('h:i A', strtotime($this->startTime)),
                'endTime' => date('h:i A', strtotime($this->endTime)),
                'grade' => $this->grade,
                'room' => $this->room,
                'classFee' => $this->classFee,
                'feeType' => $this->feeType,
                'status' => $this->status,
                'subject' => new SubjectResource($this->subject),
                'category' => $this->category,
                'branch' => [
                    'branchID' => $this->branch->branchID,
                    'branchName' => $this->branch->branchName,
                ],
                'exams' => ExamResource::collection($this->whenLoaded('exams')),
            ];
        }

        return [
            'classID' => $this->classID,
            'className' => $this->className,
            'day' => $this->day,
            'startTime' => date('h:i A', strtotime($this->startTime)),
            'endTime' => date('h:i A', strtotime($this->endTime)),
            'grade' => $this->grade,
            'room' => $this->room,
            'classFee' => $this->classFee,
            'feeType' => $this->feeType,
            'status' => $this->status,
            'students_count' => $this->whenNotNull($this->students_count),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
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
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
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
