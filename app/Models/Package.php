<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price_per_kg',
        'unit',
        'estimated_hours',
        'priority_level',
        'description',
        'features',
        'popular',
        'bg_gradient',
        'badge_text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'popular' => 'boolean',
            'is_active' => 'boolean',
            'price_per_kg' => 'decimal:2',
            'estimated_hours' => 'integer',
            'priority_level' => 'integer',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'package_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_kg, 0, ',', '.');
    }
}

