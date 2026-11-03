<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Request $request, $invoiceNo = null)
    {
        $param = $invoiceNo ?? $request->query('inv') ?? $request->query('ticket');

        if ($param) {
            $cleanParam = ltrim($param, '#');
            $ticket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
                ->where('ticket_no', $cleanParam)
                ->orWhere('ticket_no', '#' . $cleanParam)
                ->orWhereHas('invoice', function ($iq) use ($param, $cleanParam) {
                    $iq->where('invoice_no', $param)->orWhere('invoice_no', $cleanParam);
                })
                ->first();
        } else {
            $ticket = ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])
                ->where('ticket_no', 'SC-2026-9042')
                ->first() ?? ServiceOrder::with(['customer', 'technician', 'diagnostic', 'items.sparepart', 'invoice.warranty'])->latest()->first();
        }

        return view('invoice', compact('ticket'));
    }
}
