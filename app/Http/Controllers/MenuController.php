<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuRecipe;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $request) {
        // Mulai merangkai Query pencarian
        $query = Menu::orderBy('created_at', 'desc');

        // Jika ada filter "Tipe" yang dipilih (Satuan / Paket)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Jika ada filter "Kategori" yang dipilih (Nasi Box / Prasmanan / dll)
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Eksekusi data dengan batasan 10 baris per halaman
        // appends() digunakan agar saat pindah halaman 2, filternya tidak hilang
        $menus = $query->paginate(10)->appends($request->query());

        return view('admin.menus.index', compact('menus'));
    }

    public function create() {
        $materials = RawMaterial::orderBy('name', 'asc')->get();
        return view('admin.menus.create', compact('materials'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required',
            'category' => 'required',
            'selling_price' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::create([
                'name' => $request->name,
                'type' => $request->type,
                'category' => $request->category,
                'selling_price' => $request->selling_price,
                'description' => $request->description,
                'current_cogs' => 0 
            ]);

            $total_hpp = 0;
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    if(!isset($item['material_id'])) continue;
                    
                    $material = RawMaterial::find($item['material_id']);
                    MenuRecipe::create([
                        'menu_id' => $menu->id,
                        'material_id' => $material->id,
                        'quantity' => $item['qty'],
                        'unit' => $material->unit
                    ]);
                    $total_hpp += ($item['qty'] * $material->moving_average_price);
                }
            }

            $menu->update(['current_cogs' => $total_hpp]);
            DB::commit();
            return redirect()->route('admin.menus.index')->with('success', 'Menu dan Resep berhasil diracik dan disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $menu = Menu::with('recipes')->findOrFail($id);
        $materials = RawMaterial::orderBy('name', 'asc')->get();
        return view('admin.menus.edit', compact('menu', 'materials'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required',
            'category' => 'required',
            'selling_price' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            $menu->update([
                'name' => $request->name,
                'type' => $request->type,
                'category' => $request->category,
                'selling_price' => $request->selling_price,
                'description' => $request->description,
            ]);

            MenuRecipe::where('menu_id', $menu->id)->delete();

            $total_hpp = 0;
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    if(!isset($item['material_id'])) continue;

                    $material = RawMaterial::find($item['material_id']);
                    MenuRecipe::create([
                        'menu_id' => $menu->id,
                        'material_id' => $material->id,
                        'quantity' => $item['qty'],
                        'unit' => $material->unit
                    ]);
                    $total_hpp += ($item['qty'] * $material->moving_average_price);
                }
            }

            $menu->update(['current_cogs' => $total_hpp]);
            DB::commit();
            return redirect()->route('admin.menus.index')->with('success', 'Pembaruan data menu dan resep berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        try {
            Menu::findOrFail($id)->delete();
            return back()->with('success', 'Menu berhasil dihapus dari katalog.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Menu ini mungkin sedang terikat dalam histori pesanan pelanggan.');
        }
    }
}