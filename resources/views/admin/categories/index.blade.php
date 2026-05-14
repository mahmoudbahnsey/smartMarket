@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="page-header">
    <div>
        <h1>Categories</h1>
        <p>Manage product categories</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td style="color:var(--text-muted);">{{ $cat->id }}</td>
                    <td style="font-weight:600;">{{ $cat->name }}</td>
                    <td style="font-size:.8rem;color:var(--text-muted);">{{ $cat->slug }}</td>
                    <td><span class="badge badge-info">{{ $cat->products_count }}</span></td>
                    <td>
                        <span class="badge badge-{{ $cat->is_active ? 'success' : 'danger' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <button class="btn btn-outline btn-xs" onclick="openEdit({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->description }}', {{ $cat->is_active ? 1 : 0 }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted);">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal-overlay" id="add-modal">
    <div class="modal">
        <div class="modal-header">
            <span><i class="fas fa-plus" style="color:var(--primary);margin-right:.5rem;"></i>Add Category</span>
            <span class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">&times;</span>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Electronics" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('add-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="edit-modal">
    <div class="modal">
        <div class="modal-header">
            <span><i class="fas fa-edit" style="color:var(--primary);margin-right:.5rem;"></i>Edit Category</span>
            <span class="modal-close" onclick="document.getElementById('edit-modal').classList.remove('open')">&times;</span>
        </div>
        <form method="POST" id="edit-form">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit-desc" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" id="edit-active" value="1">
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('edit-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEdit(id, name, desc, active) {
    document.getElementById('edit-form').action = `/admin/categories/${id}`;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-desc').value = desc || '';
    document.getElementById('edit-active').checked = active == 1;
    document.getElementById('edit-modal').classList.add('open');
}
</script>
@endpush
