@extends('layouts.admin')

@section('header_title', 'Add User')

@section('admin_content')
<div class="admin-form-container" style="max-width: 650px;">
    <form action="{{ url('admin/users') }}" method="POST">
        @csrf
        
        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; border: 1px solid #fca5a5;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="input-field" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="input-field" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="input-field" required minlength="8">
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="input-field" id="role-select" required>
                <option value="author" {{ old('role') == 'author' ? 'selected' : '' }}>Author</option>
                <option value="sub-admin" {{ old('role') == 'sub-admin' ? 'selected' : '' }}>Sub-Admin</option>
                <option value="super-admin" {{ old('role') == 'super-admin' ? 'selected' : '' }}>Super-Admin</option>
            </select>
        </div>

        <div class="form-group" id="permissions-group">
            <label class="form-label">Permissions (For Sub-Admins / Authors)</label>
            <div style="display: flex; flex-direction: column; gap: 0.65rem; background: var(--surface); padding: 1rem; border-radius: 8px; border: 1px solid var(--border);">
                @php $oldPerms = old('permissions', []); @endphp
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_posts" {{ in_array('manage_posts', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Posts
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_categories_menus" {{ in_array('manage_categories_menus', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Categories & Menus
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_media" {{ in_array('manage_media', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Media
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_newsletters" {{ in_array('manage_newsletters', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Newsletters
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_users" {{ in_array('manage_users', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Users (Careful)
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_ads_popups" {{ in_array('manage_ads_popups', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Ads & Popups
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="permissions[]" value="manage_settings" {{ in_array('manage_settings', $oldPerms) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent);"> Manage Settings
                </label>
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Note: Super-Admins automatically have all permissions.</p>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; margin-top: 1.5rem;">
            <a href="{{ url('/admin/users') }}" class="btn-action" style="padding: 0.75rem 1.5rem; text-decoration: none;">Cancel</a>
            <button type="submit" class="btn-submit" style="padding: 0.75rem 2rem;">Create User</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('role-select').addEventListener('change', function() {
        const group = document.getElementById('permissions-group');
        if (this.value === 'super-admin') {
            group.style.opacity = '0.5';
            group.style.pointerEvents = 'none';
        } else {
            group.style.opacity = '1';
            group.style.pointerEvents = 'auto';
        }
    });
    document.getElementById('role-select').dispatchEvent(new Event('change'));
</script>
@endsection
