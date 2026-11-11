<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Perbarui view_bi_service_orders dengan metrik DP, Outstanding, Aging Piutang, dan Derived Payment Status
        DB::statement("
            CREATE OR REPLACE VIEW view_bi_service_orders AS
            SELECT 
                so.id AS ticket_id,
                so.ticket_no,
                so.branch_code,
                so.lead_source,
                so.created_at AS order_created_at,
                DATE(so.created_at) AS order_date,
                so.customer_id,
                so.customer_type,
                so.company_name,
                u_cust.name AS customer_name,
                u_cust.phone AS customer_phone,
                so.crm_segment_snapshot,
                so.device_type,
                so.device_brand,
                so.device_model,
                so.serial_number,
                d.symptom_category,
                d.fault_category,
                so.delivery_method,
                so.handover_method,
                so.order_status,
                so.negotiation_status,
                so.negotiation_budget,
                so.technician_id,
                u_tech.name AS technician_name,
                u_tech.specialization AS technician_specialization,
                COALESCE(items_stat.total_parts_qty, 0) AS total_parts_qty,
                COALESCE(items_stat.total_parts_cost_price, 0) AS total_parts_cost_price,
                COALESCE(items_stat.total_parts_selling_price, 0) AS total_parts_selling_price,
                COALESCE(items_stat.total_parts_margin, 0) AS total_parts_margin,
                COALESCE(d.labor_cost, 0) AS labor_cost,
                COALESCE(d.addon_cleaning_cost, 0) AS addon_cleaning_cost,
                COALESCE(d.addon_repasta_cost, 0) AS addon_repasta_cost,
                COALESCE(inv.amount_total, d.net_total_cost, 0) AS net_revenue_total,
                (COALESCE(d.labor_cost, 0) + COALESCE(d.addon_cleaning_cost, 0) + COALESCE(d.addon_repasta_cost, 0) + COALESCE(items_stat.total_parts_margin, 0)) AS gross_profit_estimate,
                
                -- Metrik Keuangan Multi-Trans & DP
                COALESCE(pay_stat.total_dp_paid, 0) AS total_dp_paid,
                COALESCE(pay_stat.total_settlement_paid, 0) AS total_settlement_paid,
                COALESCE(pay_stat.total_refunded, 0) AS total_refunded,
                COALESCE(pay_stat.net_amount_paid, 0) AS net_amount_paid,
                (COALESCE(inv.amount_total, d.net_total_cost, 0) - COALESCE(pay_stat.net_amount_paid, 0)) AS outstanding_balance,
                
                -- Derived Status Pembayaran
                CASE 
                    WHEN COALESCE(pay_stat.net_amount_paid, 0) <= 0 THEN 'Unpaid'
                    WHEN COALESCE(pay_stat.net_amount_paid, 0) > 0 AND COALESCE(pay_stat.net_amount_paid, 0) < COALESCE(inv.amount_total, d.net_total_cost, 0) THEN 'Partial (DP)'
                    WHEN COALESCE(pay_stat.net_amount_paid, 0) = COALESCE(inv.amount_total, d.net_total_cost, 0) THEN 'Paid'
                    ELSE 'Overpaid'
                END AS derived_payment_status,
                
                inv.due_date,
                -- Aging Piutang (Hari keterlambatan dari due_date jika masih ada outstanding)
                CASE 
                    WHEN (COALESCE(inv.amount_total, d.net_total_cost, 0) - COALESCE(pay_stat.net_amount_paid, 0)) > 0 
                         AND inv.due_date IS NOT NULL 
                         AND CURRENT_DATE > inv.due_date 
                    THEN DATEDIFF(CURRENT_DATE, inv.due_date)
                    ELSE 0
                END AS receivable_aging_days,

                inv.payment_method AS invoice_payment_method,
                COALESCE(pay_stat.payments_count, 0) AS total_payments_count,
                so.unit_handed_over,
                so.courier_name,
                so.receiver_name,
                CASE WHEN so.signature_data IS NOT NULL THEN 1 ELSE 0 END AS has_digital_signature,
                so.handed_over_at,
                so.sla_auto_close_at,
                so.rating_score,
                w.warranty_code,
                w.warranty_duration_days,
                w.warranty_status,
                TIMESTAMPDIFF(MINUTE, so.created_at, so.handed_over_at) / 60.0 AS tat_total_hours
            FROM service_orders so
            LEFT JOIN users u_cust ON so.customer_id = u_cust.id
            LEFT JOIN users u_tech ON so.technician_id = u_tech.id
            LEFT JOIN diagnostics d ON so.id = d.service_order_id
            LEFT JOIN invoices inv ON so.id = inv.service_order_id
            LEFT JOIN warranties w ON inv.id = w.invoice_id
            LEFT JOIN (
                SELECT 
                    service_order_id,
                    SUM(quantity) AS total_parts_qty,
                    SUM(cost_price * quantity) AS total_parts_cost_price,
                    SUM(unit_price * quantity) AS total_parts_selling_price,
                    SUM(margin_total) AS total_parts_margin
                FROM order_items
                GROUP BY service_order_id
            ) items_stat ON so.id = items_stat.service_order_id
            LEFT JOIN (
                SELECT 
                    invoice_id,
                    COUNT(*) AS payments_count,
                    SUM(CASE WHEN status = 'Success' AND (payment_type = 'DP' OR payment_type = 'Down Payment (DP)') THEN amount ELSE 0 END) AS total_dp_paid,
                    SUM(CASE WHEN status = 'Success' AND (payment_type = 'PELUNASAN' OR payment_type = 'FULL' OR payment_type = 'Full Payment' OR payment_type = 'Final Settlement (Pelunasan)') THEN amount ELSE 0 END) AS total_settlement_paid,
                    SUM(CASE WHEN payment_type = 'REFUND' OR status = 'Refunded' THEN amount ELSE 0 END) AS total_refunded,
                    (SUM(CASE WHEN status = 'Success' AND payment_type != 'REFUND' THEN amount ELSE 0 END) - SUM(CASE WHEN payment_type = 'REFUND' OR status = 'Refunded' THEN amount ELSE 0 END)) AS net_amount_paid
                FROM payments
                GROUP BY invoice_id
            ) pay_stat ON inv.id = pay_stat.invoice_id
        ");
    }

    public function down(): void
    {
        // Tetap aman
    }
};
