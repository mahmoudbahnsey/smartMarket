@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h1>Users</h1>
        <p>Manage all system users</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">
        <i class="fas fa-user-plus"></i> Add User
    </button>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.5rem;">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div style="min-width:150px;">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="branch_manager" {{ request('role') == 'branch_manager' ? 'selected' : '' }}>Branch Manager</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;font-size:.875rem;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.85rem;color:var(--text-muted);">{{ $user->email }}</td>
                    <td>
                        @php $rb = match($user->role){'admin'=>'danger','branch_manager'=>'warning','customer'=>'info',default=>'secondary'}; @endphp
                        <span class="badge badge-{{ $rb }}">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                    </td>
                    <td style="font-size:.85rem;color:var(--text-muted);">{{ $user->phone ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-xs" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                            @else
                            <span style="font-size:.75rem;color:var(--text-muted);">You</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div style="padding:1rem 1.5rem;">
        <div class="pagination">{{ $users->links() }}</div>
    </div>
    @endif
</div>

{{-- Add User Modal --}}
<div class="modal-overlay" id="add-modal">
    <div class="modal">
        <div class="modal-header">
            <span><i class="fas fa-user-plus" style="color:var(--primary);margin-right:.5rem;"></i>Add New User</span>
            <span class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">&times;</span>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+20...">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="customer">Customer</option>
                        <option value="branch_manager">Branch Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('add-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
