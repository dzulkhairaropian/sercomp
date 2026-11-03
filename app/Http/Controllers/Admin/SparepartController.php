<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $spareparts = Sparepart::orderBy('part_name')->get();

        $totalSku = $spareparts->count();
        $totalPcs = $spareparts->sum('stock_qty');
        $lowStockCount = $spareparts->where('stock_qty', '<=', 3)->count();

        $totalValuation = $spareparts->sum(function ($part) {
            return $part->stock_qty * $part->unit_price;
        });

        return view('admin.spareparts', compact(
            'spareparts',
            'totalSku',
            'totalPcs',
            'lowStockCount',
            'totalValuation'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_code' => 'required|string|max:50|unique:spareparts,part_code',
            'part_name' => 'required|string|max:150',
            'category' => 'required|in:Fan,RAM,SSD,Screen,PSU,Cable',
            'stock_qty' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $part = Sparepart::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Suku cadang {$part->part_name} ({$part->part_code}) berhasil ditambahkan!",
                'sparepart' => $part,
            ]);
        }

        return back()->with('success', 'Suku cadang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $part = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'part_name' => 'sometimes|required|string|max:150',
            'category' => 'sometimes|required|in:Fan,RAM,SSD,Screen,PSU,Cable',
            'stock_qty' => 'sometimes|required|integer|min:0',
            'unit_price' => 'sometimes|required|numeric|min:0',
        ]);

        $part->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Stok suku cadang {$part->part_name} berhasil diperbarui!",
                'sparepart' => $part,
            ]);
        }

        return back()->with('success', 'Stok suku cadang berhasil diperbarui.');
    }
}
