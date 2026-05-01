<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard() {
        $today = Carbon::today()->format('Y-m-d');

        // 1. Metrik Operasional
        $orders_today = DB::table('orders')->whereDate('order_date', $today)->count();
        $low_stock_items = DB::table('raw_materials')->whereRaw('current_stock <= min_stock')->count();
        $unpaid_invoices = DB::table('purchase_invoices')->where('status', 'unpaid')->count();

        // 2. Metrik Finansial (Bulan Ini)
        $this_month = Carbon::now()->startOfMonth();
        
        $total_revenue = DB::table('orders')
            ->where('order_date', '>=', $this_month)
            ->whereIn('payment_status', ['paid', 'dp'])
            ->sum('grand_total');

        // Menghitung Estimasi HPP dari pesanan yang masuk
        $total_cogs = DB::table('order_items')
            ->join('order_schedules', 'order_items.schedule_id', '=', 'order_schedules.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('order_schedules.delivery_date', '>=', $this_month)
            ->select(DB::raw('SUM(order_items.quantity * menus.current_cogs) as total_hpp'))
            ->first()->total_hpp ?? 0;

        $net_profit = $total_revenue - $total_cogs;

        // 3. Data Grafik 7 Hari Terakhir
        $labels = [];
        $data_rev = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            $data_rev[] = (float) DB::table('orders')
                ->whereDate('order_date', $date->format('Y-m-d'))
                ->whereIn('payment_status', ['paid', 'dp'])
                ->sum('grand_total');
        }

        return view('admin.dashboard', compact(
            'orders_today', 'low_stock_items', 'unpaid_invoices',
            'total_revenue', 'total_cogs', 'net_profit',
            'labels', 'data_rev'
        ));
    }

    public function kelolaKonten() {
        return redirect()->route('admin.konten');
    }
}