<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginRecordResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'record';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'userID' => $this->userID,
            'username' => $this->user->username,
            'loginDate' => $this->loginDate,
            'loginTime' => date('h:i A', strtotime($this->loginTime)),
            'logoutTime' => $this->logoutTime != null ? date('h:i A', strtotime($this->logoutTime)) : null,
            'name' => $this->user->employee->title . ' ' . $this->user->employee->person->firstName . ' ' . $this->user->employee->person->lastName,
            'employeeType' => $this->user->employee->employeeType,
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
