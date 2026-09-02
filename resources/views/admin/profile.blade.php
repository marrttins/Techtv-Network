@extends('layouts.admin')

@section('header_title', 'Admin Profile & Security')

@section('admin_content')

{{-- ============================================================
     1. FLASH MESSAGES & ALERTS
     ============================================================ --}}
@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem; box-shadow: 0 2px 6px rgba(22, 101, 52, 0.05);">
        <span style="font-size: 1.2rem;">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; font-size: 0.9rem;">
        <div style="font-weight: 700; margin-bottom: 0.35rem;">Please review the following errors:</div>
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ============================================================
     2. PROFILE HEADER SUMMARY BANNER
     ============================================================ --}}
<div style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); border-radius: 12px; padding: 1.5rem; color: #ffffff; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: 0 8px 25px rgba(11, 25, 60, 0.15);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--accent, #e02020); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 4px 12px rgba(0,0,0,0.3); flex-shrink: 0;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.3rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #ffffff;">
                {{ $user->name }}
            </h2>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.85rem; color: #cbd5e1; word-break: break-all;">{{ $user->email }}</span>
                <span style="font-size: 0.72rem; background: rgba(255,255,255,0.15); color: #ffffff; padding: 0.15rem 0.55rem; border-radius: 9999px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                    {{ $user->role }}
                </span>
            </div>
        </div>
    </div>

    <div style="font-size: 0.8rem; color: #94a3b8;">
        <div>Account Created</div>
        <strong style="color: #f1f5f9; font-size: 0.88rem;">{{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</strong>
    </div>
</div>

{{-- ============================================================
     3. TWO-COLUMN EDIT FORMS
     ============================================================ --}}
<div class="admin-grid-2" style="align-items: flex-start;">

    {{-- LEFT CARD: PERSONAL INFORMATION --}}
    <div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="border-left: 3px solid var(--accent); padding-left: 0.75rem; margin-bottom: 1.5rem;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0;">
                Profile Information
            </h3>
            <p style="font-size: 0.82rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
                Update your account's public name and administrative email address.
            </p>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    Full Name *
                </label>
                <input type="text" name="name" class="input-field" value="{{ old('name', $user->name) }}" required placeholder="Enter your full name">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    Email Address *
                </label>
                <input type="email" name="email" class="input-field" value="{{ old('email', $user->email) }}" required placeholder="admin@example.com">
                <small style="color: var(--admin-text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">This email is used for login and administrative notifications.</small>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    Assigned Role
                </label>
                <input type="text" class="input-field" value="{{ ucwords(str_replace('-', ' ', $user->role)) }}" disabled style="background: #f8fafc; color: #64748b; cursor: not-allowed;">
                <small style="color: var(--admin-text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">Roles can only be altered by a Super Administrator.</small>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="padding: 0.75rem 2rem; border: none; cursor: pointer;">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>

    {{-- RIGHT CARD: CHANGE PASSWORD --}}
    <div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="border-left: 3px solid #d97706; padding-left: 0.75rem; margin-bottom: 1.75rem;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0;">
                Change Password
            </h3>
            <p style="font-size: 0.82rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
                Ensure your account is using a long, secure password to stay protected.
            </p>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    Current Password *
                </label>
                <input type="password" name="current_password" class="input-field" required placeholder="••••••••">
                <small style="color: var(--admin-text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">Enter your existing password to authorize this change.</small>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    New Password *
                </label>
                <input type="password" name="password" class="input-field" required placeholder="••••••••">
                <small style="color: var(--admin-text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">Minimum 8 characters (letters, numbers, and symbols recommended).</small>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="display: block; font-weight: 700; font-size: 0.88rem; color: #334155; margin-bottom: 0.45rem;">
                    Confirm New Password *
                </label>
                <input type="password" name="password_confirmation" class="input-field" required placeholder="••••••••">
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); padding: 0.75rem 2rem; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(11,25,60,0.25);">
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
