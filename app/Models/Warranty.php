<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'warranty_code',
        'warranty_duration_days',
        'starts_at',
        'expires_at',
        'warranty_status',
    ];

    protected function casts(): array
    {
        return [
            'warranty_duration_days' => 'integer',
            'starts_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
