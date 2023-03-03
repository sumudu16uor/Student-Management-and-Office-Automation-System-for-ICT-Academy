<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Enrollment extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enrollment';

//    /**
//     * The primary key associated with the table.
//     *
//     * @var string
//     */
//    protected $primaryKey = 'studentID';
//
//    /**
//     * Indicates if the model's ID is auto-incrementing.
//     *
//     * @var bool
//     */
//    public $incrementing = false;
//
//    /**
//     * The data type of the auto-incrementing ID.
//     *
//     * @var string
//     */
//    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'paymentStatus' => '1',
        'status' => '1',
    ];

    protected $casts = [
        'paymentStatus' => 'integer',
        'enrolledDate' => 'timestamp:Y-m-d',
        'status' => 'integer'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
