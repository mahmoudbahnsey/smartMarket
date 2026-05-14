<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $branches = Branch::where('is_active', true)->get();
        return view('shop.checkout', compact('cart', 'branches'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'branch_id'       => 'required|exists:branches,id',
            'shipping_address'=> 'required|string|max:500',
            'shipping_city'   => 'required|string|max:100',
            'payment_method'  => 'required|in:cash,card,online',
            'notes'           => 'nullable|string|max:500',
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        DB::transaction(function () use ($cart, $data) {
            $subtotal = $cart->items->sum(fn($i) => $i->product->current_price * $i->quantity);
            $tax      = round($subtotal * 0.14, 2);
            $total    = $subtotal + $tax;

            $order = Order::create([
                'user_id'          => Auth::id(),
                'branch_id'        => $data['branch_id'],
                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'total_price'      => $total,
                'payment_method'   => $data['payment_method'],
                'shipping_address' => $data['shipping_address'],
                'shipping_city'    => $data['shipping_city'],
                'notes'            => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->current_price,
                    'subtotal'   => $item->product->current_price * $item->quantity,
                ]);

                // Deduct from inventory
                Inventory::where('product_id', $item->product_id)
                         ->where('branch_id', $data['branch_id'])
                         ->decrement('quantity', $item->quantity);
            }

            // Clear cart after order
            $cart->items()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function index()
    {
        $orders = Order::with(['branch', 'items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('shop.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load(['branch', 'items.product', 'user']);
        return view('shop.order-detail', compact('order'));
    }
}
