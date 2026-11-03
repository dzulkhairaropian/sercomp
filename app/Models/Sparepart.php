<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_code',
        'part_name',
        'category',
        'stock_qty',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'stock_qty' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getNameAttribute(): string
    {
        return $this->part_name;
    }

    public function getStockAttribute(): int
    {
        return $this->stock_qty;
    }

    public function getSellingPriceAttribute(): float
    {
        return (float) $this->unit_price;
    }

    public function getCostPriceAttribute(): float
    {
        return (float) round($this->unit_price * 0.75);
    }

    public function getMinStockAlertAttribute(): int
    {
        return 3;
    }
}
