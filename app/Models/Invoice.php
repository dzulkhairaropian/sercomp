<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'invoice_no',
        'amount_total',
        'initial_estimate',
        'revised_estimate',
        'due_date',
        'payment_method',
        'payment_status',
        'qris_transaction_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_total' => 'decimal:2',
            'initial_estimate' => 'decimal:2',
            'revised_estimate' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Hitung total uang yang sudah berhasil dibayarkan (Total Paid).
     * Rumus: SUM(Success where payment_type != 'REFUND') - SUM(Success where payment_type = 'REFUND' OR status = 'Refunded')
     */
    public function getTotalPaidAttribute(): float
    {
        $paid = (float) $this->payments()
            ->where('status', 'Success')
            ->whereNotIn('payment_type', ['REFUND'])
            ->sum('amount');

        $refunded = (float) $this->payments()
            ->where(function ($q) {
                $q->where('payment_type', 'REFUND')
                  ->orWhere('status', 'Refunded');
            })
            ->sum('amount');

        return max(0.0, $paid - $refunded);
    }

    /**
     * Sisa tagihan / piutang (Outstanding).
     * net_total - total_paid
     */
    public function getOutstandingAttribute(): float
    {
        return (float) ($this->amount_total - $this->total_paid);
    }

    /**
     * Status Pembayaran Turunan (Derived Status):
     * - Unpaid (total_paid == 0)
     * - Partial (0 < total_paid < net_total)
     * - Paid (total_paid == net_total)
     * - Overpaid (total_paid > net_total)
     */
    public function getDerivedPaymentStatusAttribute(): string
    {
        $paid = $this->total_paid;
        $total = (float) $this->amount_total;

        if ($paid <= 0) {
            return 'Unpaid';
        }

        if ($paid > 0 && $paid < $total) {
            return 'Partial'; // Pembayaran bertahap / DP sudah masuk tapi belum lunas
        }

        if ($paid == $total) {
            return 'Paid';
        }

        return 'Overpaid'; // Kelebihan bayar (misal ada negosiasi / revisi turun setelah DP)
    }

    /**
     * Apakah unit memenuhi syarat lolos gate penyerahan (Handover Gate)?
     * Khusus Personal: wajib outstanding <= 0.
     * Khusus B2B Corporate termin (due_date aktif): diizinkan handover dengan status termin berjalan.
     */
    public function getCanHandoverAttribute(): bool
    {
        if ($this->outstanding <= 0) {
            return true;
        }

        // B2B Corporate NET 30 exception
        if ($this->due_date && $this->serviceOrder?->customer_type === 'Company') {
            return true;
        }

        return false;
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function warranty(): HasOne
    {
        return $this->hasOne(Warranty::class);
    }
}
