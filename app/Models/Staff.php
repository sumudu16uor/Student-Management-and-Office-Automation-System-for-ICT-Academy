<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Staff extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staff';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'staffID';

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
     * Get the fees handled by staff.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'handlerStaffID', 'staffID');
    }

    /**
     * Get the branch that owns the staff.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branchID', 'branchID');
    }

    /**
     * Get the expenditures handled by staff.
     */
    public function expenditures(): HasMany
    {
        return $this->hasMany(Expenditure::class, 'handlerStaffID', 'staffID');
    }

    /**
     * Get the advances handled by staff.
     */
    public function advances(): HasMany
    {
        return $this->hasMany(Advance::class, 'handlerStaffID', 'staffID');
    }

    /**
     * Get the staff's parent model.
     */
    public function employee(): MorphOne
    {
        return $this->morphOne(Employee::class, 'employable', 'employeeType', 'employeeID');
    }

    /**
     * Get the processes handled by staff.
     */
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class, 'handlerStaffID', 'staffID');
    }
}
