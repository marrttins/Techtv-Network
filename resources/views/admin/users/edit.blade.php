@extends('layouts.admin')

@section('header_title', 'Edit User')

@section('admin_content')
<div class="post-editor" style="max-width: 600px;">
    <form action="{{ url('admin/users/' . $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
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
            <input type="text" name="name" class="input-field" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="input-field" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password <span style="font-weight: normal; color: var(--text-muted);">(Leave blank to keep current)</span></label>
            <input type="password" name="password" class="input-field" minlength="8">
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="input-field" id="role-select" required>
                <option value="author" {{ old('role', $user->role) == 'author' ? 'selected' : '' }}>Author</option>
                <option value="sub-admin" {{ old('role', $user->role) == 'sub-admin' ? 'selected' : '' }}>Sub-Admin</option>
                <option value="super-admin" {{ old('role', $user->role) == 'super-admin' ? 'selected' : '' }}>Super-Admin</option>
            </select>
        </div>

        <div class="form-group" id="permissions-group">
            <label class="form-label">Permissions</label>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: var(--bg); padding: 1rem; border-radius: 6px; border: 1px solid var(--border);">
                @php $oldPerms = old('permissions', $user->permissions ?? []); @endphp
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_posts" {{ in_array('manage_posts', $oldPerms) ? 'checked' : '' }}> Manage Posts
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_categories_menus" {{ in_array('manage_categories_menus', $oldPerms) ? 'checked' : '' }}> Manage Categories & Menus
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_media" {{ in_array('manage_media', $oldPerms) ? 'checked' : '' }}> Manage Media
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_newsletters" {{ in_array('manage_newsletters', $oldPerms) ? 'checked' : '' }}> Manage Newsletters
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_users" {{ in_array('manage_users', $oldPerms) ? 'checked' : '' }}> Manage Users (Careful)
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_ads_popups" {{ in_array('manage_ads_popups', $oldPerms) ? 'checked' : '' }}> Manage Ads & Popups
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_settings" {{ in_array('manage_settings', $oldPerms) ? 'checked' : '' }}> Manage Settings
                </label>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 6px; width: 100%; border: none; cursor: pointer; font-size: 1rem;">Update User</button>
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
