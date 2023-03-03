<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'categoryID';

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
     * Get the subjects for the category.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'categoryID', 'categoryID');
    }

    /**
     * Get the classes for the category.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'categoryID', 'categoryID');
    }

    /**
     * Get the exams for the category.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'categoryID', 'categoryID');
    }
}
