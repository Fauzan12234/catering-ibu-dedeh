<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function profitLoss(Request $request) {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $revenue = DB::table('orders')
            ->whereMonth('order_date', $month)
            ->whereYear('order_date', $year)
            ->whereIn('payment_status', ['paid', 'dp'])
            ->sum('grand_total');

        $cogs = DB::table('order_items')
            ->join('order_schedules', 'order_items.schedule_id', '=', 'order_schedules.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->whereMonth('order_schedules.delivery_date', $month)
            ->whereYear('order_schedules.delivery_date', $year)
            ->select(DB::raw('SUM(order_items.quantity * menus.current_cogs) as hpp'))
            ->first()->hpp ?? 0;

        return view('admin.reports.profit_loss', compact('revenue', 'cogs', 'month', 'year'));
    }
}