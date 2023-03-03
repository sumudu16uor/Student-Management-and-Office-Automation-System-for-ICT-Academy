<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advances';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'advanceID';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'advanceAmount' => 'decimal:2',
        'date' => 'timestamp:Y-m-d',
        'deleted_at' => 'timestamp:Y-m-d h:i A'
    ];

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
     * Get the staff who handles the advance.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'handlerStaffID', 'staffID');
    }

    /**
     * Get the employee who received the advance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employeeID', 'employeeID');
    }
}
