<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan dengan fitur Filter & Pencarian
     */
    public function index(Request $request) {
        $query = Order::with('customer')->orderBy('order_date', 'desc');

        // Filter berdasarkan Pencarian Nama/Ref
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('order_ref', 'like', "%$search%")
                  ->orWhereHas('customer', function($c) use ($search) {
                      $c->where('name', 'like', "%$search%");
                  });
            });
        }

        // Filter berdasarkan Status Pembayaran
        if ($request->has('status') && $request->status != '') {
            $query->where('payment_status', $request->status);
        }

        $orders = $query->paginate(10)->appends($request->query());
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Form Input Pesanan Baru
     */
    public function create() {
        $menus = Menu::orderBy('name', 'asc')->get();
        // Format: ORD-20260430-123
        $order_ref = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        
        return view('admin.orders.create', compact('menus', 'order_ref'));
    }

    /**
     * Menyimpan Pesanan Baru (Transaksi Aman dengan DB Transaction)
     */
    public function store(Request $request) {
        $request->validate([
            'customer_name' => 'required|string',
            'order_type' => 'required',
            'delivery_date' => 'required|date',
            'items' => 'required|array',
            'grand_total' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan/Ambil Data Pelanggan (Gunakan nomor HP sebagai ID Unik)
            $customer = Customer::firstOrCreate(
                ['phone' => $request->customer_phone],
                [
                    'name' => $request->customer_name, 
                    'address' => $request->customer_address, 
                    'type' => 'individual'
                ]
            );

            // 2. Buat Header Order
            $order = Order::create([
                'order_ref' => $request->order_ref,
                'customer_id' => $customer->id,
                'order_type' => $request->order_type,
                'order_date' => date('Y-m-d'),
                'grand_total' => $request->grand_total,
                'payment_status' => 'unpaid' // Default belum bayar
            ]);

            // 3. Buat Jadwal Pengiriman (Schedule)
            $schedule_id = DB::table('order_schedules')->insertGetId([
                'order_id' => $order->id,
                'delivery_date' => $request->delivery_date,
                'status' => 'pending',
                'created_at' => now()
            ]);

            // 4. Simpan Item Menu yang Dipesan
            foreach ($request->items as $item) {
                if(!isset($item['menu_id'])) continue;

                DB::table('order_items')->insert([
                    'schedule_id' => $schedule_id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price']
                ]);
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Detail Pesanan & Invoice
     */
    public function show($id) {
        // Load relasi agar data tersedia di view invoice
        $order = Order::with(['customer'])->findOrFail($id);
        
        // Ambil jadwal pengiriman pertama untuk invoice ini
        $schedule = DB::table('order_schedules')->where('order_id', $id)->first();
        
        if (!$schedule) {
            return back()->with('error', 'Data jadwal pengiriman tidak ditemukan.');
        }

        // Ambil item menu yang dibeli
        $items = DB::table('order_items')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->where('schedule_id', $schedule->id)
                ->select('order_items.*', 'menus.name as menu_name')
                ->get();

        return view('admin.orders.show', compact('order', 'schedule', 'items'));
    }

    /**
     * Memperbarui Status Pembayaran (Lunas/DP/Belum Bayar)
     */
    public function updateStatus(Request $request, $id) {
        $request->validate(['status' => 'required|in:unpaid,dp,paid']);
        
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->status]);
        
        return back()->with('success', 'Status pembayaran untuk ' . $order->order_ref . ' berhasil diperbarui!');
    }

    /**
     * Hapus Pesanan
     */
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            // Otomatis menghapus jadwal dan item karena relasi DB (Cascading) atau manual jika perlu
            $order->delete();
            
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Pesanan telah dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus pesanan.');
        }
    }
}