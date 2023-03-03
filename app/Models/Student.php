<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Student extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'studentID';

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
     * Get the student that owns the branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branchID', 'branchID');
    }

    /**
     * Get the parent associated with the student.
     */
    public function parent(): HasOne
    {
        return $this->hasOne(Parents::class, 'studentID', 'studentID');
    }

    /**
     * Get the fees paid by student.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'studentID', 'studentID');
    }

    /**
     * Get the attendances for the student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'studentID', 'studentID');
    }

    /**
     * The exams that belong to the student.
     */
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'mark', 'studentID', 'examID')
            ->using(Mark::class)->as('result')
            ->withPivot('mark');
    }

    /**
     * The classes that enrolled by student.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'enrollment', 'studentID', 'classID')
            ->using(Enrollment::class)->as('enrollment')
            ->withPivot('paymentStatus', 'enrolledDate', 'status');
    }

    /**
     * Get the student's parent model.
     */
    public function person(): MorphOne
    {
        return $this->morphOne(Person::class, 'personable', 'personType', 'personID');
    }
}
