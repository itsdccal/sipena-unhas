<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'suggested_fields',
        'description',
        'is_active',
    ];

    protected $casts = [
        'suggested_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function activityDetails(): HasMany
    {
        return $this->hasMany(ActivityDetail::class);
    }

    public function getSuggestedFieldNames(): array
    {
        $fields = $this->suggested_fields['fields'] ?? [];
        return array_map(fn($f) => $f['label'] ?? $f['name'], $fields);
    }
}
