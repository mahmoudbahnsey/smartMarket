<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $newArrivals = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $saleProducts = Product::with('category')
            ->where('is_active', true)
            ->whereNotNull('sale_price')
            ->take(4)
            ->get();

        return view('shop.home', compact(
            'featuredProducts', 'categories', 'newArrivals', 'saleProducts'
        ));
    }

    public function products(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products  = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('shop.products', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'reviews.user', 'inventories.branch'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'related'));
    }
}
