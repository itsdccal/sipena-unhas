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

    // Auto calculate grand total
    public function recalculateGrandTotal(): void
    {
        $this->grand_total = $this->activityDetails()->sum('total');
        $this->save();
    }

    // Observer untuk auto-update grand_total
    protected static function boot()
    {
        parent::boot();

        static::created(function ($report) {
            $report->recalculateGrandTotal();
        });
    }
}
