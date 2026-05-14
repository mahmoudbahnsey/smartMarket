@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="page-header">
    <div>
        <h1>Products</h1>
        <p>Manage your product catalog</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.5rem;">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Product name..." value="{{ request('search') }}">
            </div>
            <div style="min-width:180px;">
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span>{{ $products->total() }} Products</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sale Price</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="product-thumb" alt="">
                            @else
                                <div style="width:36px;height:36px;border-radius:8px;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;color:var(--border);">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:.875rem;">{{ $product->name }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ Str::limit($product->description, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info">{{ $product->category->name ?? '—' }}</span></td>
                    <td style="font-weight:700;">${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->sale_price ? '$' . number_format($product->sale_price, 2) : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_featured)
                            <span class="badge badge-warning"><i class="fas fa-star"></i> Yes</span>
                        @else
                            <span style="color:var(--text-muted);font-size:.8rem;">No</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">
                        <i class="fas fa-box" style="font-size:2rem;display:block;margin-bottom:.75rem;"></i>
                        No products found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="padding:1rem 1.5rem;">
        <div class="pagination">{{ $products->links() }}</div>
    </div>
    @endif
</div>
@endsection
