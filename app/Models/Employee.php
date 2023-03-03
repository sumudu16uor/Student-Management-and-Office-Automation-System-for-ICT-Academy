<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Employee extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'employeeID';

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
     * Get the user associated with the employee.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employeeID', 'employeeID');
    }

    /**
     * Get the advances received by employee.
     */
    public function advances(): HasMany
    {
        return $this->hasMany(Advance::class, 'employeeID', 'employeeID');
    }

    /**
     * Get the child employable model (staff or teacher).
     */
    public function employable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'employeeType', 'employeeID');
    }

    /**
     * Get the employee's parent model.
     */
    public function person(): MorphOne
    {
        return $this->morphOne(Person::class, 'personable', 'personType', 'personID');
    }
}
