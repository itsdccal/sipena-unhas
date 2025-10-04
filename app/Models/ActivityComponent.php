<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityComponent extends Model
{
    protected $fillable = [
        'activity_detail_id',
        'component_name',
        'component_value',
        'display_order',
    ];

    protected $casts = [
        'component_value' => 'decimal:2',
        'display_order' => 'integer',
    ];

    public function activityDetail(): BelongsTo
    {
        return $this->belongsTo(ActivityDetail::class);
    }

    // Auto recalculate activity detail saat component berubah
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($component) {
            $component->activityDetail->volume = $component->activityDetail->calculateVolume();
            $component->activityDetail->save();
        });

        static::deleted(function ($component) {
            $component->activityDetail->volume = $component->activityDetail->calculateVolume();
            $component->activityDetail->save();
        });
    }
}
