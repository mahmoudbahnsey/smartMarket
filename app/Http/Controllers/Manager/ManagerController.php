<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    // ── Get the branch this manager manages ───────────────────────────────────
    private function getBranch(): Branch
    {
        $branch = Branch::where('manager_id', Auth::id())->first();
        if (!$branch) {
            abort(403, 'You are not assigned to any branch yet. Contact the admin.');
        }
        return $branch;
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $branch = $this->getBranch();

        $stats = [
            'total_orders'   => Order::where('branch_id', $branch->id)->count(),
            'pending_orders' => Order::where('branch_id', $branch->id)->where('status', 'pending')->count(),
            'revenue'        => Order::where('branch_id', $branch->id)->where('payment_status', 'paid')->sum('total_price'),
            'low_stock'      => Inventory::where('branch_id', $branch->id)->whereColumn('quantity', '<=', 'low_stock_alert')->count(),
            'total_products' => Inventory::where('branch_id', $branch->id)->count(),
        ];

        $recentOrders = Order::with(['user', 'items'])
            ->where('branch_id', $branch->id)
            ->latest()
            ->take(8)
            ->get();

        $lowStockItems = Inventory::with('product')
            ->where('branch_id', $branch->id)
            ->whereColumn('quantity', '<=', 'low_stock_alert')
            ->get();

        return view('manager.dashboard', compact('branch', 'stats', 'recentOrders', 'lowStockItems'));
    }

    // ── Orders ────────────────────────────────────────────────────────────────
    public function orders(Request $request)
    {
        $branch = $this->getBranch();

        $query = Order::with(['user', 'items'])
            ->where('branch_id', $branch->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('manager.orders', compact('branch', 'orders'));
    }

    public function orderShow(Order $order)
    {
        $branch = $this->getBranch();

        // Manager can only see orders from their branch
        if ($order->branch_id !== $branch->id) {
            abort(403, 'This order does not belong to your branch.');
        }

        $order->load(['user', 'items.product']);
        return view('manager.order-show', compact('branch', 'order'));
    }

    public function orderUpdateStatus(Request $request, Order $order)
    {
        $branch = $this->getBranch();

        if ($order->branch_id !== $branch->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    // ── Inventory ─────────────────────────────────────────────────────────────
    public function inventory(Request $request)
    {
        $branch = $this->getBranch();

        $query = Inventory::with('product.category')
            ->where('branch_id', $branch->id);

        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity', '<=', 'low_stock_alert');
        }

        $inventories = $query->paginate(20)->withQueryString();

        return view('manager.inventory', compact('branch', 'inventories'));
    }

    public function inventoryUpdate(Request $request, Inventory $inventory)
    {
        $branch = $this->getBranch();

        if ($inventory->branch_id !== $branch->id) {
            abort(403);
        }

        $request->validate([
            'quantity'        => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
        ]);

        $inventory->update($request->only('quantity', 'low_stock_alert'));

        return back()->with('success', 'Stock updated successfully.');
    }

    // ── Products (view only — manager sees products in their branch) ──────────
    public function products(Request $request)
    {
        $branch = $this->getBranch();

        $query = Inventory::with('product.category')
            ->where('branch_id', $branch->id);

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $inventories = $query->paginate(15)->withQueryString();

        return view('manager.products', compact('branch', 'inventories'));
    }
}
