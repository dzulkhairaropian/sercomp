<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceTicket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_completed' => 'boolean',
            'unit_handed_over' => 'boolean',
            'is_closed' => 'boolean',
            'paid_at' => 'datetime',
            'approved_at' => 'datetime',
            'completed_at' => 'datetime',
            'unit_handed_over_at' => 'datetime',
            'sla_auto_close_deadline' => 'datetime',
            'closed_at' => 'datetime',
            'warranty_valid_until' => 'date',
        ];
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceTicketItem::class);
    }

    public function slaLogs(): HasMany
    {
        return $this->hasMany(SlaLog::class);
    }
}
