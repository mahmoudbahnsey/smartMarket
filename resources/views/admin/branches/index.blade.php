@extends('layouts.admin')
@section('title', 'Branches')
@section('page-title', 'Branches')

@section('content')
<div class="page-header">
    <div>
        <h1>Branches</h1>
        <p>Manage store branches</p>
    </div>
    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Branch
    </a>
</div>

<div class="grid-3" style="margin-bottom:1.5rem;">
    @forelse($branches as $branch)
    <div class="card" style="transition:transform .2s,border-color .2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.borderColor='rgba(249,115,22,.4)'" onmouseout="this.style.transform='';this.style.borderColor=''">
        <div class="card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem;">
                <div style="width:44px;height:44px;background:rgba(249,115,22,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:1.2rem;">
                    <i class="fas fa-building"></i>
                </div>
                <span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}">
                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:.4rem;">{{ $branch->name }}</h3>
            <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:.75rem;">
                <i class="fas fa-map-marker-alt" style="color:var(--primary);margin-right:.3rem;"></i>{{ $branch->location }}
            </p>
            @if($branch->phone)
            <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:.5rem;">
                <i class="fas fa-phone" style="color:var(--primary);margin-right:.3rem;"></i>{{ $branch->phone }}
            </p>
            @endif
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1rem;padding-top:.75rem;border-top:1px solid var(--border);">
                <div style="font-size:.8rem;color:var(--text-muted);">
                    <i class="fas fa-shopping-bag" style="margin-right:.3rem;"></i>{{ $branch->orders_count }} orders
                </div>
                @if($branch->manager)
                <div style="font-size:.8rem;color:var(--text-muted);">
                    <i class="fas fa-user" style="margin-right:.3rem;"></i>{{ $branch->manager->name }}
                </div>
                @else
                <span style="font-size:.75rem;color:var(--warning);">No manager</span>
                @endif
            </div>
            <div style="display:flex;gap:.5rem;margin-top:1rem;">
                <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Delete this branch?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted);">
        <i class="fas fa-building" style="font-size:3rem;display:block;margin-bottom:1rem;"></i>
        No branches yet. <a href="{{ route('admin.branches.create') }}" style="color:var(--primary);">Add one</a>
    </div>
    @endforelse
</div>
@endsection
