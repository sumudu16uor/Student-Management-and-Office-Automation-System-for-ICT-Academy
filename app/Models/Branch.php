<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branches';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'branchID';

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
     * Get the staff for the branch.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'branchID', 'branchID');
    }

    /**
     * Get the students for the branch.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'branchID', 'branchID');
    }

    /**
     * Get the classes for the branch.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'branchID', 'branchID');
    }

    /**
     * Get the expenditures for the branch.
     */
    public function expenditures(): HasMany
    {
        return $this->hasMany(Expenditure::class, 'branchID', 'branchID');
    }

    /**
     * Get the advances for the branch.
     */
    public function advances(): HasMany
    {
        return $this->hasMany(Advance::class, 'branchID', 'branchID');
    }

    /**
     * Get the exams for the branch.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'branchID', 'branchID');
    }

    /**
     * Get the fees for the branch.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'branchID', 'branchID');
    }

    /**
     * Get the process for the branch.
     */
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class, 'branchID', 'branchID');
    }
}
