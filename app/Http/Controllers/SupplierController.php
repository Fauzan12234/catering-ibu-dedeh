<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        // Menyimpan hanya nama dan telepon
        Supplier::create($request->only(['name', 'phone']));
        
        return back()->with('success', 'Data Supplier berhasil didaftarkan.');
    }

    public function destroy($id) {
        try {
            Supplier::findOrFail($id)->delete();
            return back()->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Supplier ini mungkin masih terikat dengan histori faktur belanja.');
        }
    }
}