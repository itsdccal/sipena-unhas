<?php
// app/Models/User.php
// Tambahkan ke model User yang sudah ada

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'study_program_id', // TAMBAHKAN INI
        'name',
        'nip',
        'password',
        'role',              // TAMBAHKAN INI
        'status',            // TAMBAHKAN INI
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // TAMBAHKAN RELASI INI
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
