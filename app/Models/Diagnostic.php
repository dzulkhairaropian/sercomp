<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnostic extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'physical_damage_analysis',
        'symptom_category',
        'fault_category',
        'action_plan',
        'labor_cost',
        'parts_cost',
        'addon_cleaning_cost',
        'addon_repasta_cost',
        'net_total_cost',
        'evidence_running_test_photo',
        'evidence_replaced_part_photo',
        'is_approved_by_user',
        'approved_at',
        'estimated_by',
        'admin_notes',
        'is_price_locked',
    ];

    protected function casts(): array
    {
        return [
            'labor_cost' => 'decimal:2',
            'parts_cost' => 'decimal:2',
            'addon_cleaning_cost' => 'decimal:2',
            'addon_repasta_cost' => 'decimal:2',
            'net_total_cost' => 'decimal:2',
            'is_approved_by_user' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function estimator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estimated_by');
    }
}
