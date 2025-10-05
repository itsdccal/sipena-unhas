<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'study_program_id',
        'semester_id',
        'user_id',
        'program_type',
        'grand_total',
    ];

    protected $casts = [
        'grand_total' => 'decimal:2',
    ];

    // Relationships
    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityDetails(): HasMany
    {
        return $this->hasMany(ActivityDetail::class);
    }

    // Recalculate grand total dari semua activity details
    public function recalculateGrandTotal(): void
    {
        $this->grand_total = $this->activityDetails()->sum('total');
        $this->saveQuietly(); // Save tanpa trigger event lagi
    }
}
