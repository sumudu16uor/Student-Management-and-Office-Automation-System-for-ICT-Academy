<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'classID';

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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'Active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'startTime' => 'timestamp:h:i A',
        'endTime' => 'timestamp:h:i A',
        'grade' => 'string',
        'classFee' => 'decimal:2'
    ];

    /**
     * Get the teacher that owns the class.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacherID', 'teacherID');
    }

    /**
     * Get the class that owns the branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branchID', 'branchID');
    }

    /**
     * Get the attendances for the class.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'classID', 'classID');
    }

    /**
     * Get the fees for the class.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'classID', 'classID');
    }

    /**
     * Get the category that owns the class.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'categoryID', 'categoryID');
    }

    /**
     * Get the subject that owns the class.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class,'subjectID', 'subjectID');
    }

    /**
     * Get the exams for the class.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'classID', 'classID');
    }

    /**
     * The students who enroll to the class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollment', 'classID', 'studentID')
            ->using(Enrollment::class)->as('enrollment')
            ->withPivot('paymentStatus', 'enrolledDate', 'status');
    }
}
