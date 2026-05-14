<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ── Allowed MIME types for product images ────────────────────────────────
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const MAX_IMAGE_SIZE = 2048; // KB

    public function index(Request $request)
    {
        // SQL Injection safe: Eloquent uses PDO prepared statements
        $query = Product::with('category');

        if ($request->filled('search')) {
            // Safe: uses parameter binding internally
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            // Safe: cast to int prevents injection
            $query->where('category_id', (int) $request->category);
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // ── Strict validation prevents SQL injection & XSS at input level ────
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0|max:999999',
            'sale_price'  => 'nullable|numeric|min:0|max:999999|lt:price',
            'category_id' => 'required|integer|exists:categories,id',
            // Secure file upload: only real images, max 2MB, checked by MIME
            'image'       => [
                'nullable',
                'file',
                'max:' . self::MAX_IMAGE_SIZE,
                'mimes:jpeg,jpg,png,webp,gif',
            ],
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $data['slug']        = Str::slug($data['name']) . '-' . uniqid();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $this->secureImageUpload($request, 'image');
        }

        Product::create($data);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0|max:999999',
            'sale_price'  => 'nullable|numeric|min:0|max:999999',
            'category_id' => 'required|integer|exists:categories,id',
            'image'       => [
                'nullable',
                'file',
                'max:' . self::MAX_IMAGE_SIZE,
                'mimes:jpeg,jpg,png,webp,gif',
            ],
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            // Delete old image securely
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->secureImageUpload($request, 'image');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // ── Secure image upload ───────────────────────────────────────────────────
    // Validates MIME type from file content (not just extension) to prevent
    // malicious file uploads disguised as images.
    private function secureImageUpload(Request $request, string $field): string
    {
        $file = $request->file($field);

        // Double-check real MIME type from file content
        $realMime = $file->getMimeType();
        if (!in_array($realMime, self::ALLOWED_MIMES)) {
            abort(422, 'Invalid file type. Only images are allowed.');
        }

        // Generate a random filename to prevent path traversal attacks
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::random(40) . '.' . strtolower($extension);

        // Store in public disk under products/
        return $file->storeAs('products', $filename, 'public');
    }
}
