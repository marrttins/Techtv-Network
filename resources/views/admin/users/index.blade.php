@extends('layouts.admin')

@section('header_title', 'Users & Roles')

@section('admin_content')
<div>
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
         <h3 style="font-size: 1.15rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">Manage Users</h3>
         <a href="{{ url('admin/users/create') }}" class="btn-submit" style="padding: 0.55rem 1.25rem; font-size: 0.85rem; text-decoration: none;">+ Add User</a>
    </div>

    @if(session('success'))
        <div style="background: #16a34a; color: white; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: #dc2626; color: white; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.9rem;">
            {{ $errors->first() }}
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table-admin" style="min-width: 650px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Joined</th>
                    <th style="text-align: right; width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="font-weight: 600; color: #1e293b;">{{ $user->name }}</td>
                        <td style="color: #0284c7; word-break: break-all;">{{ $user->email }}</td>
                        <td>
                            <span class="badge-status badge-publish" style="text-transform: capitalize;">
                                {{ str_replace('-', ' ', $user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->role === 'super-admin')
                                <span style="font-size: 0.82rem; color: #16a34a; font-weight: 700;">All Permissions</span>
                            @elseif($user->permissions)
                                <span style="font-size: 0.8rem; color: #64748b;">{{ implode(', ', array_map(function($p) { return str_replace('_', ' ', $p); }, $user->permissions)) }}</span>
                            @else
                                <span style="font-size: 0.8rem; color: #94a3b8;">None</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap; font-size: 0.82rem; color: #64748b;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                <a href="{{ url('admin/users/' . $user->id . '/edit') }}" class="action-btn action-edit" title="Edit">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ url('admin/users/' . $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-delete" title="Delete" style="background:none; border:none; cursor:pointer;">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
