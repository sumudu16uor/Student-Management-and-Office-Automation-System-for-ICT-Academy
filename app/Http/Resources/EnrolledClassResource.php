<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrolledClassResource extends JsonResource
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
        return [
            'classID' => $this->classID,
            'className' => $this->className,
            'feeType' => $this->feeType,
            'classFee' => $this->classFee,
            'paymentStatus'=> $this->enrollment->paymentStatus,
            'enrolledDate' => $this->enrollment->enrolledDate,
            'status' => $this->enrollment->status,
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
