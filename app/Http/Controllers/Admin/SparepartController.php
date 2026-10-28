<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Tampilkan katalog & stok suku cadang workshop.
     */
    public function index(Request $request)
    {
        $spareparts = Sparepart::where('is_active', true)->orderBy('name')->get();

        $totalSku = $spareparts->count();
        $totalPcs = $spareparts->sum('stock');
        $lowStockCount = $spareparts->filter(function ($part) {
            return $part->stock <= $part->min_stock_alert;
        })->count();

        // Estimasi valuasi aset stok
        $totalValuation = $spareparts->sum(function ($part) {
            return $part->stock * $part->cost_price;
        });

        return view('admin.spareparts', compact(
            'spareparts',
            'totalSku',
            'totalPcs',
            'lowStockCount',
            'totalValuation'
        ));
    }

    /**
     * Tambah item suku cadang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_code' => 'required|string|unique:spareparts,part_code',
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_alert' => 'nullable|integer|min:1',
        ]);

        $part = Sparepart::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Suku cadang baru berhasil ditambahkan ke inventori.',
                'sparepart' => $part,
            ]);
        }

        return back()->with('success', 'Suku cadang baru berhasil disimpan.');
    }

    /**
     * Perbarui jumlah stok dan harga suku cadang.
     */
    public function update(Request $request, $id)
    {
        $part = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $part->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Stok item {$part->name} berhasil diperbarui.",
                'sparepart' => $part,
            ]);
        }

        return back()->with('success', "Stok item {$part->name} berhasil diperbarui.");
    }
}
