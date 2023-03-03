<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'examID';

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
        'totalMark' => 100
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'timestamp:Y-m-d'
    ];

    /**
     * Get the exam that owns the branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branchID', 'branchID');
    }

    /**
     * Get the class that owns the exam.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'classID', 'classID');
    }

    /**
     * Get the category that owns the exam.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoryID', 'categoryID');
    }

    /**
     * Get the subject that owns the exam.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subjectID', 'subjectID');
    }

    /**
     * The students who belong to the exam.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'mark', 'examID', 'studentID')
            ->using(Mark::class)->as('result')
            ->withPivot('mark');
    }
}
