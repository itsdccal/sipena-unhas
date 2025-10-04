<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    protected $fillable = [
        'degree_code',
        'degree_name',
    ];

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class);
    }
}
