<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    // Fitur Belanja H-1 (Prediksi Kebutuhan)
    public function shoppingList(Request $request) {
        $target_date = $request->date ?? Carbon::tomorrow()->format('Y-m-d');

        // Query Sakti: Menghitung total kebutuhan bahan baku berdasarkan resep menu yang dipesan
        $requirements = DB::table('order_items')
            ->join('order_schedules', 'order_items.schedule_id', '=', 'order_schedules.id')
            ->join('menu_recipes', 'order_items.menu_id', '=', 'menu_recipes.id')
            ->join('raw_materials', 'menu_recipes.material_id', '=', 'raw_materials.id')
            ->whereDate('order_schedules.delivery_date', $target_date)
            ->select(
                'raw_materials.id',
                'raw_materials.name',
                'raw_materials.unit',
                'raw_materials.current_stock',
                DB::raw('SUM(order_items.quantity * menu_recipes.quantity) as total_needed')
            )
            ->groupBy('raw_materials.id', 'raw_materials.name', 'raw_materials.unit', 'raw_materials.current_stock')
            ->get();

        return view('admin.purchases.shopping_list', compact('requirements', 'target_date'));
    }

    // Halaman Input Nota Pembelian (Hari H)
    public function create() {
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $materials = RawMaterial::orderBy('name', 'asc')->get();
        return view('admin.purchases.create', compact('suppliers', 'materials'));
    }

    // Simpan Nota & Update HPP (Moving Average)
    public function store(Request $request) {
        $request->validate([
            'supplier_id' => 'required',
            'invoice_date' => 'required|date',
            'items' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $total_invoice = 0;
            foreach ($request->items as $item) {
                $total_invoice += ($item['qty'] * $item['price']);
            }

            // 1. Simpan Induk Nota
            $invoice_id = DB::table('purchase_invoices')->insertGetId([
                'supplier_id' => $request->supplier_id,
                'invoice_ref' => 'INV-' . time(),
                'invoice_date' => $request->invoice_date,
                'total_amount' => $total_invoice,
                'status' => 'paid',
                'created_at' => now()
            ]);

            // 2. Proses tiap item belanja
            foreach ($request->items as $item) {
                $material = RawMaterial::find($item['material_id']);
                
                // Rumus Moving Average untuk HPP Final:
                // ((Stok Lama * HPP Lama) + (Stok Baru * Harga Baru)) / (Total Stok)
                $old_total_value = $material->current_stock * $material->moving_average_price;
                $new_purchase_value = $item['qty'] * $item['price'];
                $new_total_qty = $material->current_stock + $item['qty'];
                
                $new_hpp = ($new_total_qty > 0) 
                           ? ($old_total_value + $new_purchase_value) / $new_total_qty 
                           : $item['price'];

                // Update Data Material
                $material->update([
                    'current_stock' => $new_total_qty,
                    'moving_average_price' => $new_hpp,
                    'last_purchase_price' => $item['price']
                ]);

                DB::table('purchase_items')->insert([
                    'invoice_id' => $invoice_id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price']
                ]);
            }

            // 3. Update Otomatis HPP di semua Menu yang menggunakan bahan ini
            $this->recalculateAllMenuHPP();

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Nota belanja berhasil disimpan. HPP semua menu telah diperbarui otomatis.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function recalculateAllMenuHPP() {
        $menus = \App\Models\Menu::all();
        foreach ($menus as $menu) {
            $total_hpp = 0;
            foreach ($menu->recipes as $recipe) {
                $total_hpp += ($recipe->quantity * $recipe->material->moving_average_price);
            }
            $menu->update(['current_cogs' => $total_hpp]);
        }
    }
}