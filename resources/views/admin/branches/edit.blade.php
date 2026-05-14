@extends('layouts.admin')
@section('title', 'Edit Branch')
@section('page-title', 'Edit Branch')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb"><a href="{{ route('admin.branches.index') }}">Branches</a><span>/</span><span>Edit</span></div>
        <h1>Edit: {{ $branch->name }}</h1>
    </div>
    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="max-width:700px;">
    <form method="POST" action="{{ route('admin.branches.update', $branch) }}">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><i class="fas fa-building" style="color:var(--primary);margin-right:.5rem;"></i>Branch Information</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Branch Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $branch->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $branch->city) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Location / Area *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $branch->location) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $branch->address) }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Branch Manager (One-to-One)</label>
                    <select name="manager_id" class="form-control">
                        <option value="">— No Manager —</option>
                        @foreach($managers as $mgr)
                            <option value="{{ $mgr->id }}" {{ old('manager_id', $branch->manager_id) == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                        <span>Active Branch</span>
                    </label>
                </div>
                <div style="display:flex;gap:1rem;margin-top:.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Branch</button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
