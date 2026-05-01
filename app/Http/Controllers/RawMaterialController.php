<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index() {
        $materials = RawMaterial::orderBy('name', 'asc')->get();
        return view('admin.raw_materials.index', compact('materials'));
    }

    public function store(Request $request) {
        $request->validate([
            'sku' => 'nullable|string|unique:raw_materials',
            'name' => 'required|string|max:100',
            'category' => 'required|string',
            'unit' => 'required|string',
            'min_stock' => 'required|numeric'
        ]);

        RawMaterial::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
            'current_stock' => 0,
            'moving_average_price' => 0,
            'last_purchase_price' => 0,
        ]);

        return back()->with('success', 'Bahan Baku baru berhasil dimasukkan ke katalog gudang.');
    }

    public function destroy($id) {
        try {
            RawMaterial::findOrFail($id)->delete();
            return back()->with('success', 'Bahan Baku berhasil dihapus dari katalog.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Bahan baku ini masih digunakan di dalam resep menu atau histori transaksi.');
        }
    }
}