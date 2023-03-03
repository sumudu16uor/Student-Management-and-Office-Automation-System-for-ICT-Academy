<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'exam';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->routeIs('classes.show.exams')) {
            return [
                'examID' => $this->examID,
                'exam' => $this->exam,
                'totalMark' => $this->totalMark,
                'date' => $this->date,
            ];
        }

        if ($request->routeIs('teachers.show.classesWithExam')) {
            return [
                'examID' => $this->examID,
                'exam' => $this->exam,
                'totalMark' => $this->totalMark,
                'date' => $this->date,
            ];
        }

        return [
            'examID' => $this->examID,
            'exam' => $this->exam,
            'totalMark' => $this->totalMark,
            'date' => $this->date,
            'class' => [
                'classID' => $this->class->classID,
                'className' => $this->class->className,
                'day' => $this->class->day,
                'grade' => $this->class->grade,
            ],
            'subject' => [
                'subjectID' => $this->subject->subjectID,
                'subjectName' => $this->subject->subjectName,
                'medium' => $this->subject->medium,
            ],
            'category' => $this->category,
            'branchID' => [
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
