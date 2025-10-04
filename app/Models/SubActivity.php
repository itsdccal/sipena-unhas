<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubActivity extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function activityDetails(): HasMany
    {
        return $this->hasMany(ActivityDetail::class);
    }
}
