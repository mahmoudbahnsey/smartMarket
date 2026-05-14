<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['product.category', 'branch']);

        if ($request->filled('branch')) {
            $query->where('branch_id', $request->branch);
        }
        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity', '<=', 'low_stock_alert');
        }

        $inventories = $query->paginate(20)->withQueryString();
        $branches    = Branch::all();

        $lowStockCount = Inventory::whereColumn('quantity', '<=', 'low_stock_alert')->count();

        return view('admin.inventory.index', compact('inventories', 'branches', 'lowStockCount'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'quantity'        => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
        ]);

        $inventory->update($data);
        return back()->with('success', 'Inventory updated.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'      => 'required|exists:products,id',
            'branch_id'       => 'required|exists:branches,id',
            'quantity'        => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
        ]);

        Inventory::updateOrCreate(
            ['product_id' => $data['product_id'], 'branch_id' => $data['branch_id']],
            ['quantity' => $data['quantity'], 'low_stock_alert' => $data['low_stock_alert']]
        );

        return back()->with('success', 'Inventory record saved.');
    }
}
