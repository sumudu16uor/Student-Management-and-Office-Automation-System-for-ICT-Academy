<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fees';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'feeID';

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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'paidStatus' => 'P'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'timestamp:Y-m-d',
        'paidAmount' => 'decimal:2',
        'paidStatus' => 'string',
        'deleted_at' => 'timestamp:Y-m-d h:i A'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the staff who handles the fee.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'handlerStaffID', 'staffID');
    }

    /**
     * Get the student who paid the fee.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'studentID', 'studentID');
    }

    /**
     * Get the class that owns the fee.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'classID', 'classID');
    }

    /**
     * Get the fee that owns the branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branchID', 'branchID');
    }
}
