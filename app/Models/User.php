<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'study_program_id',
        'name',
        'nip',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    // Relationships
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }
}
