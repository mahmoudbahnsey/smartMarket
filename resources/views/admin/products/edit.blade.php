@extends('layouts.admin')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span> <span>Edit</span>
        </div>
        <h1>Edit: {{ $product->name }}</h1>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid-2" style="align-items:start;">
        <div>
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-header"><i class="fas fa-info-circle" style="color:var(--primary);margin-right:.5rem;"></i>Product Information</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price *</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sale Price</label>
                            <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-header"><i class="fas fa-image" style="color:var(--primary);margin-right:.5rem;"></i>Product Image</div>
                <div class="card-body">
                    @if($product->image)
                    <div style="margin-bottom:1rem;">
                        <img src="{{ asset('storage/' . $product->image) }}" style="width:100%;border-radius:10px;max-height:200px;object-fit:cover;">
                        <p style="font-size:.75rem;color:var(--text-muted);margin-top:.4rem;">Current image</p>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Replace Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="img-preview" style="display:none;margin-top:.75rem;">
                        <img id="preview-img" src="" style="width:100%;border-radius:10px;max-height:200px;object-fit:cover;">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="fas fa-cog" style="color:var(--primary);margin-right:.5rem;"></i>Settings</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span>Active (visible in store)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <span>Featured Product</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem;">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('img-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
