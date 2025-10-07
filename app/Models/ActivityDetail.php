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
        'activity_name',
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
            // Calculate total = volume × unit_price
            $detail->total = $detail->volume * $detail->unit_price;

            // Calculate unit_cost berdasarkan allocation
            // Jika allocation = beban (pembagi), maka: total / allocation
            if ($detail->allocation > 0) {
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
