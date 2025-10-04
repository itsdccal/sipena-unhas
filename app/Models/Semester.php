<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = [
        'semester_code',
        'semester_name',
        'academic_year',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
