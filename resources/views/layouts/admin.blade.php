<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('header_title', 'Dashboard') | TechTV Admin Suite</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ file_exists(public_path('assets/css/app.css')) ? filemtime(public_path('assets/css/app.css')) : time() }}">
    
    <style>
        :root {
            --admin-sidebar-bg: #0B193C;
            --admin-sidebar-hover: rgba(255, 255, 255, 0.08);
            --admin-sidebar-active: #e02020;
            --admin-sidebar-text: #94a3b8;
            --admin-sidebar-text-active: #ffffff;
            --admin-bg: #f8fafc;
            --admin-card-bg: #ffffff;
            --admin-border: #e2e8f0;
            --admin-text-main: #1e293b;
            --admin-text-muted: #64748b;
        }

        html, body {
            height: auto !important;
            min-height: 100vh !important;
            overflow-x: clip !important;
            background-color: var(--admin-bg);
            font-family: 'Inter', sans-serif;
            color: var(--admin-text-main);
            margin: 0;
            padding: 0;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background: #0B193C;
            position: relative;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0B193C 0%, #081028 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            top: 0;
            height: 100vh;
            max-height: 100vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .admin-sidebar-brand {
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
        }

        .admin-sidebar-badge {
            background: rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.68rem;
            font-weight: 800;
            padding: 0.2rem 0.5rem;
            border-radius: 9999px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .admin-menu {
            list-style: none;
            padding: 1.25rem 1rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .admin-menu-heading {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.75rem 0.85rem 0.35rem;
        }

        .admin-menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--admin-sidebar-text);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .admin-menu-link:hover {
            background-color: var(--admin-sidebar-hover);
            color: #ffffff;
            transform: translateX(3px);
        }

        .admin-menu-link.active {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-weight: 700;
            border-left: 3px solid #38bdf8;
            box-shadow: none;
        }

        .admin-menu-icon {
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
        }

        /* User Profile in Sidebar */
        .admin-sidebar-user {
            padding: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .admin-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #1e293b;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .admin-user-info {
            flex: 1;
            min-width: 0;
        }

        .admin-user-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-user-role {
            font-size: 0.72rem;
            color: #94a3b8;
            text-transform: capitalize;
        }

        /* Main Content Container */
        .admin-content {
            flex-grow: 1;
            padding: 2.25rem 2.5rem 4rem;
            background-color: var(--admin-bg);
            min-width: 0;
        }

        /* Header Bar */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.25rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-header-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--admin-text-main);
            margin: 0.2rem 0 0 0;
            line-height: 1.2;
        }

        .admin-header-subtitle {
            font-size: 0.85rem;
            color: var(--admin-text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-admin-cta {
            background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(11, 25, 60, 0.2);
            cursor: pointer;
        }

        .btn-admin-cta:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(11, 25, 60, 0.3);
        }

        .btn-admin-outline {
            background: #ffffff;
            color: #334155;
            border: 1px solid var(--admin-border);
            padding: 0.65rem 1.15rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-admin-outline:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0B193C;
        }

        /* Metric / Stat Card */
        .stat-card-modern {
            background: #ffffff;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card-modern::before {
            display: none;
        }

        .stat-card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }

        .stat-card-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--admin-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-card-value {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--admin-text-main);
            margin: 0.35rem 0 0 0;
            line-height: 1.1;
        }

        .stat-card-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Tables */
        .table-admin {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--admin-border);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .table-admin th {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--admin-border);
            text-align: left;
        }

        .table-admin td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
            vertical-align: middle;
        }

        .table-admin tr:hover td {
            background-color: #fcfcfd;
        }

        .table-admin tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .badge-status {
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 9999px;
            display: inline-block;
            letter-spacing: 0.03em;
        }

        .badge-publish, .badge-published, .badge-active {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-draft, .badge-inactive {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Action Buttons */
        .btn-action {
            padding: 0.4rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #334155;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-action:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: var(--accent, #e02020);
        }

        .btn-delete {
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-delete:hover {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.45rem;
            color: #1e293b;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .input-field:focus {
            border-color: var(--accent, #e02020);
            box-shadow: 0 0 0 3px rgba(224, 32, 32, 0.15);
        }

        textarea.input-field {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            padding: 0.75rem 1.75rem;
            background: linear-gradient(135deg, #e02020 0%, #b91c1c 100%);
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(224, 32, 32, 0.25);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(224, 32, 32, 0.35);
        }

        /* Mobile Admin Topbar & Drawer */
        .admin-mobile-topbar {
            display: none;
            background: #0B193C;
            padding: 0.85rem 1.25rem;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-mobile-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 1.25rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(3px);
            z-index: 9998;
        }

        .admin-sidebar-backdrop.is-open {
            display: block;
        }

        .admin-sidebar-close {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #ffffff;
            font-size: 1.25rem;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        @media (max-width: 1024px) {
            .admin-layout {
                flex-direction: column;
                background: var(--admin-bg);
            }
            .admin-mobile-topbar {
                display: flex;
            }
            .admin-sidebar-close {
                display: flex;
            }
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 280px;
                height: 100vh;
                max-height: 100vh;
                transform: translateX(-100%);
                z-index: 9999;
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);
            }
            .admin-sidebar.is-open {
                transform: translateX(0);
            }
            .admin-content {
                padding: 1.5rem 1rem 3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Top Navigation Header (<1024px) -->
    <div class="admin-mobile-topbar">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <button type="button" class="admin-mobile-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle Admin Menu">
                ☰
            </button>
            <span style="font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.05rem; letter-spacing: 0.02em;">
                TechTV Admin
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <a href="{{ url('/') }}" target="_blank" style="color: #94a3b8; font-size: 0.82rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                <span>🌐 Site</span>
            </a>
        </div>
    </div>

    <!-- Backdrop Overlay for Mobile Drawer -->
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleAdminSidebar()"></div>

    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-brand">
                <a href="{{ url('/') }}" class="admin-sidebar-logo" target="_blank">
                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="TechTV Logo" style="height: 36px; width: auto; border-radius: 4px;">
                </a>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="admin-sidebar-badge">ADMIN</span>
                    <button type="button" class="admin-sidebar-close" onclick="toggleAdminSidebar()" aria-label="Close sidebar">
                        &times;
                    </button>
                </div>
            </div>
            
            <ul class="admin-menu">
                <li class="admin-menu-heading">Main Overview</li>
                <li>
                    <a href="{{ url('/admin') }}" class="admin-menu-link {{ request()->is('admin') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="admin-menu-heading">Content Management</li>
                @if(auth()->user() && auth()->user()->hasPermission('manage_posts'))
                <li>
                    <a href="{{ url('/admin/posts') }}" class="admin-menu-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📝</span>
                        <span>Posts & Articles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/videos') }}" class="admin-menu-link {{ request()->is('admin/videos*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📹</span>
                        <span>TechTV Videos</span>
                    </a>
                </li>
                @endif
                @if(auth()->user() && auth()->user()->hasPermission('manage_categories_menus'))
                <li>
                    <a href="{{ url('/admin/categories') }}" class="admin-menu-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📁</span>
                        <span>Categories & Menus</span>
                    </a>
                </li>
                @endif
                @if(auth()->user() && auth()->user()->hasPermission('manage_comments'))
                <li>
                    <a href="{{ url('/admin/comments') }}" class="admin-menu-link {{ request()->is('admin/comments*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">💬</span>
                        <span>Comments</span>
                    </a>
                </li>
                @endif
                @if(auth()->user() && auth()->user()->hasPermission('manage_media'))
                <li>
                    <a href="{{ url('/admin/media') }}" class="admin-menu-link {{ request()->is('admin/media*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">🖼️</span>
                        <span>Media Library</span>
                    </a>
                </li>
                @endif

                <li class="admin-menu-heading">Audience & Marketing</li>
                @if(auth()->user() && auth()->user()->hasPermission('manage_newsletters'))
                <li>
                    <a href="{{ url('/admin/newsletters') }}" class="admin-menu-link {{ request()->is('admin/newsletters*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📧</span>
                        <span>Newsletters</span>
                    </a>
                </li>
                @endif
                @if(auth()->user() && auth()->user()->hasPermission('manage_ads_popups'))
                <li>
                    <a href="{{ url('/admin/ads') }}" class="admin-menu-link {{ request()->is('admin/ads*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">📢</span>
                        <span>Manage Ads</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/popups') }}" class="admin-menu-link {{ request()->is('admin/popups*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">✨</span>
                        <span>Popups & Alerts</span>
                    </a>
                </li>
                @endif

                <li class="admin-menu-heading">Account & System</li>
                <li>
                    <a href="{{ url('/admin/profile') }}" class="admin-menu-link {{ request()->is('admin/profile*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">👤</span>
                        <span>My Profile</span>
                    </a>
                </li>
                @if(auth()->user() && auth()->user()->hasPermission('manage_users'))
                <li>
                    <a href="{{ url('/admin/users') }}" class="admin-menu-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">👥</span>
                        <span>Users & Roles</span>
                    </a>
                </li>
                @endif
                @if(auth()->user() && auth()->user()->hasPermission('manage_settings'))
                <li>
                    <a href="{{ url('/admin/settings') }}" class="admin-menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <span class="admin-menu-icon">⚙️</span>
                        <span>Settings</span>
                    </a>
                </li>
                @endif
                <li>
                    <form action="{{ url('/logout') }}" method="POST" id="sidebar-logout-form" style="margin: 0;">
                        @csrf
                        <button type="submit" class="admin-menu-link" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer; color: #f87171;">
                            <span class="admin-menu-icon">🚪</span>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </li>
            </ul>

            <!-- User Profile & Logout Box -->
            <div class="admin-sidebar-user">
                <a href="{{ url('/admin/profile') }}" class="admin-user-avatar" title="View & Edit Profile" style="text-decoration: none;">
                    {{ strtoupper(substr(auth()->user() ? auth()->user()->name : 'A', 0, 1)) }}
                </a>
                <a href="{{ url('/admin/profile') }}" class="admin-user-info" title="View & Edit Profile" style="text-decoration: none;">
                    <div class="admin-user-name">{{ auth()->user() ? auth()->user()->name : 'Admin' }}</div>
                    <div class="admin-user-role">{{ auth()->user() ? auth()->user()->role : 'Administrator' }} • Edit</div>
                </a>
                <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" title="Sign Out" style="background: none; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; padding: 0.35rem; border-radius: 4px; transition: color 0.2s;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#94a3b8'">
                        🚪
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <section class="admin-content">
            <header class="admin-header">
                <div>
                    <span class="admin-header-subtitle">TechTV Network • Administration</span>
                    <h1 class="admin-header-title">@yield('header_title', 'Dashboard Overview')</h1>
                </div>
                <div class="admin-header-actions">
                    <a href="{{ url('/admin/posts/create') }}" class="btn-admin-cta">
                        <span>+</span>
                        <span>New Post</span>
                    </a>
                    <a href="{{ url('/') }}" target="_blank" class="btn-admin-outline">
                        <span>🌐</span>
                        <span>View Live Site</span>
                    </a>
                    <form action="{{ url('/logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn-admin-outline" style="cursor: pointer;" title="Sign Out">
                            <span>🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Alerts -->
            @if(session('success'))
                <div style="background-color: #dcfce7; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #86efac; margin-bottom: 2rem; font-size: 0.92rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #fca5a5; margin-bottom: 2rem; font-size: 0.92rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('admin_content')
        </section>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('is-open');
                backdrop.classList.toggle('is-open');
                if (sidebar.classList.contains('is-open')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
    @yield('admin_scripts')
    @yield('scripts')
</body>
</html>
