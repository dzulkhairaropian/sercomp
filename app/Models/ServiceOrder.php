<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_type',
        'company_name',
        'technician_id',
        'ticket_no',
        'security_pin',
        'device_type',
        'device_brand',
        'device_model',
        'serial_number',
        'issue_chronology',
        'issue_photo_url',
        'addon_cleaning',
        'addon_repasta',
        'delivery_method',
        'branch_code',
        'lead_source',
        'pickup_address',
        'order_status',
        'crm_segment_snapshot',
        'negotiation_status',
        'negotiation_notes',
        'negotiation_budget',
        'courier_name',
        'handover_method',
        'receiver_name',
        'signature_data',
        'rating_score',
        'feedback_comments',
        'parent_service_order_id',
        'unit_handed_over',
        'handed_over_at',
        'sla_auto_close_at',
    ];
    protected $appends = [
        'status_step',
        'status',
        'customer_name',
        'customer_phone',
        'customer_phone_masked',
        'customer_email',
        'diagnosis',
        'action_plan',
        'cost_labor',
        'cost_parts',
        'cost_cleaning',
        'cost_repasta',
        'total_cost',
        'is_approved',
        'payment_status',
        'invoice_no',
        'is_completed',
        'is_closed',
        'unit_handed_over_at',
    ];


    protected function casts(): array
    {
        return [
            'addon_cleaning' => 'boolean',
            'addon_repasta' => 'boolean',
            'unit_handed_over' => 'boolean',
            'handed_over_at' => 'datetime',
            'sla_auto_close_at' => 'datetime',
        ];
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    public function recordStatusChange(string $newStatus, ?int $userId = null, ?string $notes = null): TicketStatusHistory
    {
        $oldStatus = $this->order_status;
        $this->update(['order_status' => $newStatus]);

        return $this->statusHistories()->create([
            'status_from' => $oldStatus,
            'status_to' => $newStatus,
            'changed_by' => $userId ?? auth()->id(),
            'notes' => $notes,
            'changed_at' => now(),
        ]);
    }

    public function parentOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'parent_service_order_id');
    }

    public function reworkOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'parent_service_order_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function diagnostic(): HasOne
    {
        return $this->hasOne(Diagnostic::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function getStatusStepAttribute(): int
    {
        return match ($this->order_status) {
            'Booking' => 1,
            'Di Workshop' => 2,
            'Approval Biaya' => 3,
            'Paid' => 4,
            'Reparasi' => 5,
            'Selesai' => 6,
            'Diserahkan', 'Closed' => 7,
            'Dibatalkan' => 0,
            default => 1,
        };
    }

    public function getStepAttribute(): int
    {
        return $this->status_step;
    }

    public function getStatusAttribute(): string
    {
        return $this->order_status;
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->customer?->name ?? 'Konsumen Guest';
    }

    public function getCustomerPhoneAttribute(): string
    {
        return $this->customer?->phone ?? '-';
    }

    public function getCustomerPhoneMaskedAttribute(): string
    {
        $phone = $this->customer?->phone;
        if (!$phone || strlen($phone) < 7) {
            return '08**-****-****';
        }
        $clean = preg_replace('/[^0-9]/', '', $phone);
        $prefix = substr($clean, 0, 4);
        $suffix = substr($clean, -4);
        return $prefix . '-****-' . $suffix;
    }

    public function getCustomerEmailAttribute(): ?string
    {
        return $this->customer?->email;
    }

    public function getDiagnosisAttribute(): ?string
    {
        return $this->diagnostic?->physical_damage_analysis;
    }

    public function getActionPlanAttribute(): ?string
    {
        return $this->diagnostic?->action_plan;
    }

    public function getCostLaborAttribute(): float
    {
        return (float) ($this->diagnostic?->labor_cost ?? 0);
    }

    public function getCostPartsAttribute(): float
    {
        return (float) ($this->diagnostic?->parts_cost ?? 0);
    }

    public function getCostCleaningAttribute(): float
    {
        return (float) ($this->diagnostic?->addon_cleaning_cost ?? ($this->addon_cleaning ? 50000 : 0));
    }

    public function getCostRepastaAttribute(): float
    {
        return (float) ($this->diagnostic?->addon_repasta_cost ?? ($this->addon_repasta ? 75000 : 0));
    }

    public function getTotalCostAttribute(): float
    {
        return (float) ($this->invoice?->amount_total ?? $this->diagnostic?->net_total_cost ?? 0);
    }

    public function getIsApprovedAttribute(): bool
    {
        return (bool) ($this->diagnostic?->is_approved_by_user ?? false);
    }

    public function getPaymentStatusAttribute(): string
    {
        return $this->invoice?->payment_status ?? 'Unpaid';
    }

    public function getInvoiceNoAttribute(): ?string
    {
        return $this->invoice?->invoice_no;
    }

    public function getIsCompletedAttribute(): bool
    {
        return in_array($this->order_status, ['Selesai', 'Diserahkan', 'Closed']);
    }

    public function getIsClosedAttribute(): bool
    {
        return $this->order_status === 'Closed';
    }

    public function getUnitHandedOverAtAttribute(): ?string
    {
        return $this->handed_over_at?->toISOString();
    }
}
