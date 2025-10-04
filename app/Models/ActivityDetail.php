<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityDetail extends Model
{
    protected $fillable = [
        'report_id',
        'unit_id',
        'sub_activity_id',
        'activity_name',
        'calculation_type',
        'volume',
        'unit_price',
        'total',
        'allocation',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'allocation' => 'integer',
    ];

    // Relationships
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function subActivity(): BelongsTo
    {
        return $this->belongsTo(SubActivity::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(ActivityComponent::class)->orderBy('display_order');
    }

    // Calculate volume dari components
    public function calculateVolume(): float
    {
        $components = $this->components;

        if ($components->isEmpty()) {
            return 0;
        }

        switch ($this->calculation_type) {
            case 'multiply':
                $result = 1;
                foreach ($components as $component) {
                    $result *= $component->component_value;
                }
                return $result;

            case 'add':
                return $components->sum('component_value');

            case 'manual':
            default:
                return $this->volume;
        }
    }

    // Display components untuk PDF
    public function getComponentsDisplay(): string
    {
        $parts = $this->components->map(function ($comp) {
            return number_format($comp->component_value, 0) . ' ' . $comp->component_name;
        })->toArray();

        $separator = $this->calculation_type === 'multiply' ? ' × ' : ' + ';
        return implode($separator, $parts);
    }

    // Auto calculate saat save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            // Recalculate volume dari components (kecuali manual)
            if ($detail->calculation_type !== 'manual' && $detail->exists) {
                $detail->volume = $detail->calculateVolume();
            }

            // Calculate total & unit_cost
            $detail->total = $detail->volume * $detail->unit_price;
            $detail->unit_cost = $detail->total * ($detail->allocation / 100);
        });

        // Update report grand_total setelah save/delete
        static::saved(function ($detail) {
            $detail->report->recalculateGrandTotal();
        });

        static::deleted(function ($detail) {
            $detail->report->recalculateGrandTotal();
        });
    }
}

