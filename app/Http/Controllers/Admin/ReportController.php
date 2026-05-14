<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $revenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->sum('total_price');

        $ordersCount = Order::whereBetween('created_at', [$from, $to . ' 23:59:59'])->count();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$from, $to . ' 23:59:59'])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        $branchRevenue = Branch::withSum(['orders as revenue' => function ($q) use ($from, $to) {
            $q->where('payment_status', 'paid')
              ->whereBetween('created_at', [$from, $to . ' 23:59:59']);
        }], 'total_price')
        ->withCount(['orders as orders_count' => function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to . ' 23:59:59']);
        }])
        ->get();

        $dailyRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $branches = Branch::all();

        return view('admin.reports.index', compact(
            'revenue', 'ordersCount', 'topProducts',
            'branchRevenue', 'dailyRevenue', 'from', 'to', 'branches'
        ));
    }
}
