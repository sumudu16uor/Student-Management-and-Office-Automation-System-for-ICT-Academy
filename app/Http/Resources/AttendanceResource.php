<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'attendance';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "studentID" => $this->studentID,
            'studentName' => $this->student->person->firstName . ' ' . $this->student->person->lastName,
            "classID" => $this->classID,
            'className' => $this->class->className,
            "date" => $this->date,
            "time" => $this->time != null ? date('h:i A', strtotime($this->time)) : null,
            "attendStatus" => $this->attendStatus,
            "paymentStatus" => $this->whenNotNull($this->paymentStatus),
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
