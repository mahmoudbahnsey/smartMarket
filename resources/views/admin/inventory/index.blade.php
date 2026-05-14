@extends('layouts.admin')
@section('title', 'Inventory')
@section('page-title', 'Inventory')

@section('content')
<div class="page-header">
    <div>
        <h1>Inventory</h1>
        <p>Manage stock levels across all branches</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">
        <i class="fas fa-plus"></i> Add Stock
    </button>
</div>

@if($lowStockCount > 0)
<div class="alert alert-warning" style="margin-bottom:1.5rem;">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>{{ $lowStockCount }} items</strong> are running low on stock!
    <a href="{{ route('admin.inventory.index', ['low_stock' => 1]) }}" style="color:inherit;text-decoration:underline;margin-left:.5rem;">View all</a>
</div>
@endif

{{-- Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.5rem;">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="min-width:180px;">
                <label class="form-label">Branch</label>
                <select name="branch" class="form-control">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;gap:.5rem;">
                <label class="form-check" style="margin-bottom:.3rem;">
                    <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }}>
                    <span style="font-size:.875rem;">Low Stock Only</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Branch</th>
                    <th>Quantity</th>
                    <th>Alert Level</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inv)
                <tr>
                    <td style="font-weight:600;font-size:.875rem;">{{ $inv->product->name }}</td>
                    <td><span class="badge badge-info">{{ $inv->product->category->name ?? '—' }}</span></td>
                    <td style="font-size:.875rem;">{{ $inv->branch->name }}</td>
                    <td>
                        <span style="font-size:1rem;font-weight:800;color:{{ $inv->quantity == 0 ? 'var(--danger)' : ($inv->isLowStock() ? 'var(--warning)' : 'var(--success)') }};">
                            {{ $inv->quantity }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.875rem;">{{ $inv->low_stock_alert }}</td>
                    <td>
                        @if($inv->quantity == 0)
                            <span class="badge badge-danger">Out of Stock</span>
                        @elseif($inv->isLowStock())
                            <span class="badge badge-warning">Low Stock</span>
                        @else
                            <span class="badge badge-success">In Stock</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-outline btn-xs" onclick="openUpdate({{ $inv->id }}, {{ $inv->quantity }}, {{ $inv->low_stock_alert }})">
                            <i class="fas fa-edit"></i> Update
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">No inventory records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inventories->hasPages())
    <div style="padding:1rem 1.5rem;">
        <div class="pagination">{{ $inventories->links() }}</div>
    </div>
    @endif
</div>

{{-- Update Modal --}}
<div class="modal-overlay" id="update-modal">
    <div class="modal">
        <div class="modal-header">
            <span><i class="fas fa-warehouse" style="color:var(--primary);margin-right:.5rem;"></i>Update Stock</span>
            <span class="modal-close" onclick="document.getElementById('update-modal').classList.remove('open')">&times;</span>
        </div>
        <form method="POST" id="update-form">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="upd-qty" class="form-control" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Low Stock Alert Level</label>
                    <input type="number" name="low_stock_alert" id="upd-alert" class="form-control" min="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('update-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Stock Modal --}}
<div class="modal-overlay" id="add-modal">
    <div class="modal">
        <div class="modal-header">
            <span><i class="fas fa-plus" style="color:var(--primary);margin-right:.5rem;"></i>Add Stock Record</span>
            <span class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">&times;</span>
        </div>
        <form method="POST" action="{{ route('admin.inventory.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">— Select Product —</option>
                        @foreach(\App\Models\Product::orderBy('name')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Branch *</label>
                    <select name="branch_id" class="form-control" required>
                        <option value="">— Select Branch —</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alert Level</label>
                        <input type="number" name="low_stock_alert" class="form-control" min="0" value="5">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('add-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openUpdate(id, qty, alert) {
    document.getElementById('update-form').action = `/admin/inventory/${id}`;
    document.getElementById('upd-qty').value = qty;
    document.getElementById('upd-alert').value = alert;
    document.getElementById('update-modal').classList.add('open');
}
</script>
@endpush
