<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Branch;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'    => Order::count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total_price'),
            'total_products'  => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_branches'  => Branch::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with(['user', 'branch'])
            ->latest()
            ->take(10)
            ->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', 'products.image', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $lowStockItems = Inventory::with(['product', 'branch'])
            ->whereColumn('quantity', '<=', 'low_stock_alert')
            ->get();

        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'topProducts', 'lowStockItems', 'monthlyRevenue'
        ));
    }
}
