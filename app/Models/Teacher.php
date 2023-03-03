<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class Teacher extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teachers';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'teacherID';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the classes for the teacher.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'teacherID', 'teacherID');
    }

    /**
     * Get the teacher's parent model.
     */
    public function employee(): MorphOne
    {
        return $this->morphOne(Employee::class, 'employable', 'employeeType', 'employeeID');
    }
}
