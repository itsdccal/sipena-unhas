<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTemplate extends Model
{
    protected $fillable = [
        'activity_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope query untuk hanya aktivitas yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
