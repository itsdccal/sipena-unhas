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

    // Display components untuk view/PDF
    public function getComponentsDisplay(): string
    {
        if ($this->components->isEmpty()) {
            return '-';
        }

        $parts = $this->components->map(function ($comp) {
            return number_format($comp->component_value, 0) . ' ' . $comp->component_name;
        })->toArray();

        $separator = $this->calculation_type === 'multiply' ? ' × ' : ' + ';
        return implode($separator, $parts);
    }

    // Get unit name
    public function getUnitName(): string
    {
        return $this->unit ? $this->unit->name : '-';
    }

    // Format currency helper
    public function getFormattedTotal(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getFormattedUnitCost(): string
    {
        return 'Rp ' . number_format($this->unit_cost, 0, ',', '.');
    }

    public function getFormattedUnitPrice(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    // Auto calculate saat save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            // Recalculate volume dari components (kecuali manual)
            if ($detail->calculation_type !== 'manual') {
                if ($detail->calculation_type === 'multiply') {
                    $result = 1;
                    foreach ($detail->components as $comp) {
                        $result *= $comp->component_value;
                    }
                    $detail->volume = $result;
                } elseif ($detail->calculation_type === 'add') {
                    $detail->volume = $detail->components->sum('component_value');
                }
            }

            // Calculate total = volume × unit_price
            $detail->total = $detail->volume * $detail->unit_price;

            // Calculate unit_cost berdasarkan allocation
            // Jika allocation = beban (pembagi), maka: total / allocation
            // Jika allocation = % beban, maka: total * (allocation / 100)
            if ($detail->allocation > 0) {
                // Versi 1: allocation sebagai pembagi (sesuai Excel Anda)
                $detail->unit_cost = $detail->total / $detail->allocation;

            } else {
                $detail->unit_cost = 0;
            }
        });

        // Update report grand_total setelah save/delete
        static::saved(function ($detail) {
            if ($detail->report) {
                $detail->report->recalculateGrandTotal();
            }
        });

        static::deleted(function ($detail) {
            if ($detail->report) {
                $detail->report->recalculateGrandTotal();
            }
        });
    }
}
