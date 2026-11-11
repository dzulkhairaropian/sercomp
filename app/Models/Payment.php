<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Tipe Pembayaran yang Diakui Arsitektur Keuangan
    public const TYPE_DP = 'DP';
    public const TYPE_PELUNASAN = 'PELUNASAN';
    public const TYPE_FULL = 'FULL';
    public const TYPE_REFUND = 'REFUND';
    public const TYPE_DIAGNOSA_FEE = 'DIAGNOSA_FEE';

    // Status Pembayaran
    public const STATUS_PENDING = 'Pending';
    public const STATUS_SUCCESS = 'Success';
    public const STATUS_FAILED = 'Failed';
    public const STATUS_REFUNDED = 'Refunded';

    protected $fillable = [
        'invoice_id',
        'payment_reference',
        'payment_type',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'received_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
