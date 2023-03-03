<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Mark extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mark';

//    /**
//     * The primary key associated with the table.
//     *
//     * @var string
//     */
//    protected $primaryKey = 'examID';
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
        'mark' => 'Ab'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mark' => 'string'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
