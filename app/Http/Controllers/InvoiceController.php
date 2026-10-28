<?php

namespace App\Http\Controllers;

use App\Models\ServiceTicket;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Tampilkan lembar faktur invoice resmi format cetak A4.
     */
    public function show(Request $request, $invoiceNo = null)
    {
        $invoiceNo = $invoiceNo ?? $request->query('inv') ?? $request->query('ticket');

        if ($invoiceNo) {
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('invoice_no', $invoiceNo)
                ->orWhere('ticket_no', $invoiceNo)
                ->orWhere('ticket_no', '#' . ltrim($invoiceNo, '#'))
                ->first();
        } else {
            $ticket = ServiceTicket::with(['technician', 'items'])
                ->where('ticket_no', '#SC-2026-9042')
                ->first() ?? ServiceTicket::with(['technician', 'items'])->latest()->first();
        }

        return view('invoice', compact('ticket'));
    }
}
