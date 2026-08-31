<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($siteSettings['site_title'] ?? 'TechTV Network') . ' | Tech & Business Broadcast')</title>
    
    <!-- Meta SEO & Canonical -->
    <meta name="description" content="@yield('meta_description', ($siteSettings['site_title'] ?? 'TechTV Network') . ' explores technology, productivity, business, entertainment and national development in Africa\'s digital economy.')">
    <meta name="keywords" content="@yield('meta_keywords', 'TechTV Network, Africa technology, Nigerian tech news, innovation, digital economy, AI, fintech, startups, Titans of Tech')">
    <link rel="canonical" href="{{ request()->url() }}">
    
    <!-- Open Graph (Facebook / LinkedIn) -->
    <meta property="og:site_name" content="{{ $siteSettings['site_title'] ?? 'TechTV Network' }}">
    <meta property="og:title" content="@yield('title', ($siteSettings['site_title'] ?? 'TechTV Network') . ' | Tech & Business Broadcast')">
    <meta property="og:description" content="@yield('meta_description', ($siteSettings['site_title'] ?? 'TechTV Network') . ' explores technology, productivity, business, entertainment and national development in Africa\'s digital economy.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/default-share.jpg'))">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', ($siteSettings['site_title'] ?? 'TechTV Network') . ' | Tech & Business Broadcast')">
    <meta name="twitter:description" content="@yield('meta_description', ($siteSettings['site_title'] ?? 'TechTV Network') . ' explores technology, productivity, business, entertainment and national development in Africa\'s digital economy.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/default-share.jpg'))">

    <!-- RSS & Feed Discovery for Google News & LLMs -->
    <link rel="alternate" type="application/rss+xml" title="{{ $siteSettings['site_title'] ?? 'TechTV Network' }} RSS Feed" href="{{ url('/feed') }}">

    <!-- Global Organization / NewsMediaOrganization Schema (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "{{ $siteSettings['site_title'] ?? 'TechTV Network' }}",
      "alternateName": ["TechTV", "TechTV Nigeria", "TechTV Africa"],
      "url": "{{ url('/') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}",
        "width": 600,
        "height": 120
      },
      "sameAs": [
        "https://www.youtube.com/@TechTVNetwork",
        "https://twitter.com/techtv_network",
        "https://facebook.com/techtvnetwork"
      ],
      "publishingPrinciples": "{{ url('/editorial-policy') }}",
      "correctionsPolicy": "{{ url('/editorial-policy') }}",
      "diversityPolicy": "{{ url('/about') }}",
      "ethicsPolicy": "{{ url('/editorial-policy') }}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "{{ $siteSettings['site_phone'] ?? '+234-800-TECHTV' }}",
        "contactType": "editorial newsdesk",
        "email": "{{ $siteSettings['site_email'] ?? 'news@techtv.com.ng' }}",
        "areaServed": ["NG", "Africa", "Global"]
      }
    }
    </script>

    <!-- Schema JSON-LD Structured Data -->
    @yield('schema_json')

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ file_exists(public_path('assets/css/app.css')) ? filemtime(public_path('assets/css/app.css')) : time() }}">
    <script>window.siteUrl = "{{ url('/') }}";</script>
    <style>
        /* ================================================================
           NAVBAR & DROPDOWN MENU STYLING
           ================================================================ */
        .site-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border, #E2E8F0);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: visible !important;
        }

        .header-nav-sub-bar {
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            overflow: visible !important;
        }

        .nav-sub-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: visible !important;
            position: relative;
        }

        .nav-sub-menu {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            list-style: none;
            margin: 0;
            padding: 0;
            overflow: visible !important;
            flex-wrap: wrap;
        }

        .nav-sub-item {
            position: relative !important;
            display: inline-flex;
            align-items: center;
            height: 48px;
        }

        .nav-sub-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.5rem 0.85rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            background: transparent !important;
            transition: color 0.2s ease, background-color 0.2s ease;
            white-space: nowrap;
            border-radius: 6px;
        }

        .nav-sub-link:hover,
        .nav-sub-link.active,
        .nav-sub-item:hover > .nav-sub-link,
        .nav-sub-item:focus-within > .nav-sub-link {
            color: var(--accent, #e02020) !important;
            background: transparent !important;
            background-color: transparent !important;
        }

        /* Dropdown Styling */
        .nav-item-dropdown {
            position: relative !important;
        }

        .nav-item-dropdown .dropdown-menu {
            display: none !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            min-width: 230px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            padding: 0.5rem 0 !important;
            z-index: 99999 !important;
            margin-top: 0 !important;
        }

        /* Desktop Hover & Focus state to show dropdown */
        @media (min-width: 1025px) {
            .header-nav-sub-bar {
                display: block !important;
                height: auto !important;
                background: #ffffff !important;
                border-top: 1px solid #f1f5f9 !important;
            }
            .nav-sub-container {
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                position: relative !important;
            }
            #nav-menu {
                position: static !important;
                display: flex !important;
                flex-direction: row !important;
                width: 100% !important;
                height: auto !important;
                background: transparent !important;
                box-shadow: none !important;
                transform: none !important;
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .mobile-drawer-body {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 0.35rem !important;
                padding: 0 !important;
                margin: 0 !important;
                flex-wrap: wrap !important;
                width: 100% !important;
            }
            .nav-sub-item {
                position: relative !important;
                display: inline-flex !important;
                flex-direction: row !important;
                align-items: center !important;
                height: 48px !important;
                border-bottom: none !important;
                width: auto !important;
            }
            .nav-sub-link {
                display: inline-flex !important;
                align-items: center !important;
                gap: 4px !important;
                padding: 0.5rem 0.85rem !important;
                width: auto !important;
                border-radius: 6px !important;
            }
            .nav-item-dropdown .dropdown-menu {
                display: none !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                min-width: 230px !important;
                background-color: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
                padding: 0.5rem 0 !important;
                z-index: 99999 !important;
                margin-top: 0 !important;
            }
            .nav-item-dropdown:hover > .dropdown-menu,
            .nav-item-dropdown:focus-within > .dropdown-menu {
                display: block !important;
                animation: navDropdownFade 0.2s ease-out forwards;
            }
            .mobile-drawer-header,
            .mobile-drawer-footer,
            .mobile-nav-backdrop {
                display: none !important;
            }
            #mobile-toggle {
                display: none !important;
            }
            .btn-submit-article {
                display: inline-flex !important;
            }
        }

        @keyframes navDropdownFade {
            from {
                opacity: 0;
                transform: translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: block !important;
            padding: 0.6rem 1.25rem !important;
            color: #334155 !important;
            text-decoration: none !important;
            font-size: 0.88rem !important;
            font-weight: 500 !important;
            background: transparent !important;
            transition: color 0.15s ease, background-color 0.15s ease !important;
            white-space: nowrap !important;
        }

        .dropdown-item:hover {
            color: var(--accent, #e02020) !important;
            background-color: #f8fafc !important;
        }

        /* ================================================================
           MOBILE DRAWER & RESPONSIVE HEADER STYLING (<= 1024px)
           ================================================================ */
        .mobile-nav-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(11, 25, 60, 0.6);
            z-index: 99990;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .mobile-nav-backdrop.is-active,
        body.mobile-nav-open .mobile-nav-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 1024px) {
            body.mobile-nav-open {
                overflow: hidden !important;
            }

            body.mobile-nav-open .site-header {
                z-index: 100000 !important;
            }

            .header-nav-sub-bar {
                border-top: none;
                height: 0;
                overflow: visible !important;
                background: transparent;
            }
            .header-nav-sub-bar .nav-sub-container {
                padding: 0;
            }

            #nav-menu {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                width: 320px !important;
                max-width: 86vw !important;
                height: 100vh !important;
                background: #ffffff !important;
                z-index: 100001 !important;
                display: flex !important;
                flex-direction: column !important;
                flex-wrap: nowrap !important;
                padding: 0 !important;
                margin: 0 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1) !important;
                box-shadow: 6px 0 30px rgba(0, 0, 0, 0.25) !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                -webkit-overflow-scrolling: touch;
            }

            #nav-menu.nav-menu--open,
            body.mobile-nav-open #nav-menu {
                transform: translateX(0) !important;
            }

            .mobile-drawer-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                padding: 1.15rem 1.35rem;
                border-bottom: 1px solid #e2e8f0;
                background: #ffffff;
                position: sticky;
                top: 0;
                z-index: 20;
                flex-shrink: 0;
            }
            .mobile-drawer-logo-img {
                height: 34px;
                width: auto;
                object-fit: contain;
            }
            .mobile-drawer-close-btn {
                background: #f1f5f9;
                border: none;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                line-height: 1;
                color: #334155;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .mobile-drawer-close-btn:hover,
            .mobile-drawer-close-btn:active {
                background: #fee2e2;
                color: #e02020;
            }

            .mobile-drawer-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                padding: 0.5rem 0;
                overflow-y: auto;
            }

            .nav-sub-item {
                display: flex !important;
                flex-direction: column !important;
                align-items: stretch !important;
                height: auto !important;
                border-bottom: 1px solid #f1f5f9 !important;
                width: 100% !important;
            }

            .nav-sub-link {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: 0.95rem 1.35rem !important;
                font-size: 0.92rem !important;
                font-weight: 600 !important;
                color: #1e293b !important;
                width: 100% !important;
                border-radius: 0 !important;
            }

            .nav-sub-link:hover,
            .nav-sub-link.active,
            .nav-sub-item.is-open > .nav-sub-link {
                color: var(--accent, #e02020) !important;
                background-color: #fff5f5 !important;
            }

            .nav-item-dropdown .dropdown-menu {
                position: static !important;
                display: none !important;
                background: #f8fafc !important;
                border: none !important;
                border-left: 3px solid var(--accent, #e02020) !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0.35rem 0 0.5rem 0 !important;
                min-width: 100% !important;
                margin: 0 !important;
            }

            .nav-item-dropdown.is-open > .dropdown-menu {
                display: block !important;
            }

            .dropdown-chevron {
                transition: transform 0.25s ease;
                margin-left: auto;
            }

            .nav-item-dropdown.is-open > .nav-sub-link .dropdown-chevron {
                transform: rotate(180deg);
            }

            .dropdown-item {
                padding: 0.65rem 1.35rem 0.65rem 1.85rem !important;
                font-size: 0.85rem !important;
                color: #475569 !important;
                font-weight: 500 !important;
            }

            .dropdown-item:hover {
                color: var(--accent, #e02020) !important;
                background-color: #f1f5f9 !important;
            }

            .mobile-drawer-footer {
                display: flex !important;
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem 1.35rem 2rem;
                border-top: 1px solid #e2e8f0;
                background: #ffffff;
                flex-shrink: 0;
            }

            .mobile-drawer-submit-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: var(--accent, #e02020);
                color: #ffffff;
                font-weight: 700;
                font-size: 0.88rem;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                text-decoration: none;
                text-align: center;
                box-shadow: 0 4px 12px rgba(224, 32, 32, 0.25);
                transition: background 0.2s;
            }

            .mobile-drawer-socials {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1.25rem;
            }

            .mobile-drawer-socials a {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                background: #f1f5f9;
                border-radius: 50%;
                color: #64748b;
                transition: all 0.2s ease;
            }

            .mobile-drawer-socials a:hover {
                background: var(--accent, #e02020);
                color: #ffffff;
            }

            #mobile-toggle {
                display: inline-flex !important;
            }

            .btn-submit-article {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- ================================================================
         HEADER
         ================================================================ -->
    <!-- Top bar -->
    <div class="header-top-bar">
        <div class="container header-top-bar-inner">
            <div class="top-bar-date">
                {{ date('l, F j, Y') }}
            </div>
            <div class="top-bar-socials">
                <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="Youtube">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile backdrop overlay -->
    <div id="mobile-nav-backdrop" class="mobile-nav-backdrop"></div>

    <header class="site-header">
        <div class="container nav-container">
            
            <!-- Logo -->
            <a href="{{ url('/') }}" class="logo-link">
                <img src="{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}" alt="{{ $siteSettings['site_title'] ?? 'TechTV Logo' }}" class="header-logo">
            </a>

            <!-- Space filler -->
            <div style="flex: 1;"></div>

            <!-- Right Actions -->
            <div class="nav-actions">
                <button class="nav-icon-btn" id="search-toggle" aria-label="Search">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </button>
                <button class="nav-icon-btn" id="mobile-toggle" aria-label="Open Menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>

                <a href="{{ url('/contact') }}" class="btn-submit-article">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    + Submit Article
                </a>
            </div>
        </div>

        <!-- ================================================================
             DYNAMIC ADMIN-CONFIGURED NAV BAR (FROM ADMIN/CATEGORIES)
             ================================================================ -->
        <div class="header-nav-sub-bar">
            <div class="container nav-sub-container" style="position:relative;">
                <nav class="nav-sub-menu" id="nav-menu">
                    <!-- Mobile Drawer Header -->
                    <div class="mobile-drawer-header">
                        <a href="{{ url('/') }}" class="mobile-drawer-logo">
                            <img src="{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}" alt="{{ $siteSettings['site_title'] ?? 'TechTV Logo' }}" class="mobile-drawer-logo-img">
                        </a>
                        <button type="button" class="mobile-drawer-close-btn" id="mobile-drawer-close" aria-label="Close menu">&times;</button>
                    </div>

                    <!-- Navigation Items Container -->
                    <div class="mobile-drawer-body">
                        @php
                            $headerMenu = \App\Models\Menu::where('location', 'header')
                                ->orWhere('location', 'primary')
                                ->with(['items' => function($q) {
                                    $q->whereNull('parent_id')->orderBy('order', 'asc')->with(['children' => function($cq) {
                                        $cq->orderBy('order', 'asc');
                                    }]);
                                }])
                                ->first() ?? \App\Models\Menu::with(['items' => function($q) {
                                    $q->whereNull('parent_id')->orderBy('order', 'asc')->with(['children' => function($cq) {
                                        $cq->orderBy('order', 'asc');
                                    }]);
                                }])->first();

                            $menuItems = $headerMenu ? $headerMenu->items : collect();
                        @endphp

                        @if($menuItems->isNotEmpty())
                            @foreach($menuItems as $item)
                                @php
                                    $rawUrl = $item->url ?: '/';
                                    if (!Str::startsWith($rawUrl, ['http://', 'https://', '/', '#'])) {
                                        $rawUrl = '/' . $rawUrl;
                                    }
                                    $finalUrl = Str::startsWith($rawUrl, ['http://', 'https://']) ? $rawUrl : url($rawUrl);
                                    $isExternal = Str::startsWith($rawUrl, ['http://', 'https://']);
                                    $isActive = !$isExternal && (request()->is(trim($rawUrl, '/')) || (request()->is('/') && $rawUrl === '/'));
                                    $hasChildren = $item->children && $item->children->count() > 0;
                                    $isWatchLive = Str::contains(Str::lower($item->label), 'watch live') || Str::contains($rawUrl, 'watch-live');
                                    $isYoutube = Str::contains(Str::lower($item->label), 'youtube');
                                @endphp

                                <div class="nav-sub-item {{ $hasChildren ? 'nav-item-dropdown' : '' }} {{ $isWatchLive ? 'nav-sub-item--live' : '' }}">
                                    <a href="{{ $finalUrl }}"
                                       class="nav-sub-link {{ $isActive ? 'active' : '' }} {{ $isWatchLive ? 'nav-sub-link--live' : '' }}"
                                       {{ $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                        @if($isWatchLive)
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: -2px; color: var(--accent, #e02020);">
                                                <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                                                <polyline points="17 2 12 7 7 2"></polyline>
                                            </svg>
                                            <span style="color: var(--accent, #e02020); font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                                                {{ $item->label }}
                                                <span class="live-dot-animate" style="width: 7px; height: 7px; border-radius: 50%; background: var(--accent, #e02020); display: inline-block;"></span>
                                            </span>
                                        @elseif($isYoutube)
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="#ff0000" style="margin-right: 4px; vertical-align: -2px; flex-shrink: 0;">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                            <span>{{ $item->label }}</span>
                                        @else
                                            <span>{{ $item->label }}</span>
                                        @endif

                                        @if($hasChildren)
                                            <svg class="dropdown-chevron" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                        @endif
                                    </a>

                                    @if($hasChildren)
                                        <div class="dropdown-menu">
                                            @foreach($item->children as $child)
                                                @php
                                                    $childRawUrl = $child->url ?: '/';
                                                    if (!Str::startsWith($childRawUrl, ['http://', 'https://', '/', '#'])) {
                                                        $childRawUrl = '/' . $childRawUrl;
                                                    }
                                                    $childFinalUrl = Str::startsWith($childRawUrl, ['http://', 'https://']) ? $childRawUrl : url($childRawUrl);
                                                    $childIsExternal = Str::startsWith($childRawUrl, ['http://', 'https://']);
                                                @endphp
                                                <a href="{{ $childFinalUrl }}" class="dropdown-item" {{ $childIsExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                                    {{ $child->label }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            {{-- Fallback default links if no menu configured --}}
                            <div class="nav-sub-item">
                                <a href="{{ url('/') }}" class="nav-sub-link {{ request()->is('/') ? 'active' : '' }}"><span>Home</span></a>
                            </div>
                            @foreach(\App\Models\Category::take(7)->get() as $fcat)
                                <div class="nav-sub-item">
                                    <a href="{{ url('/category/' . $fcat->slug) }}" class="nav-sub-link {{ request()->is('category/' . $fcat->slug . '*') ? 'active' : '' }}">
                                        <span>{{ $fcat->name }}</span>
                                    </a>
                                </div>
                            @endforeach
                            <div class="nav-sub-item">
                                <a href="{{ url('/advertise') }}" class="nav-sub-link {{ request()->is('advertise*') ? 'active' : '' }}"><span>Advertise</span></a>
                            </div>
                            <div class="nav-sub-item">
                                <a href="{{ url('/contact') }}" class="nav-sub-link {{ request()->is('contact*') ? 'active' : '' }}"><span>Contact</span></a>
                            </div>
                            <div class="nav-sub-item">
                                <a href="{{ url('/#watch-live') }}" class="nav-sub-link">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: -2px; color: var(--accent, #e02020);"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><polyline points="17 2 12 7 7 2"></polyline></svg>
                                    <span style="color: var(--accent, #e02020); font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">WATCH LIVE <span class="live-dot-animate" style="width: 7px; height: 7px; border-radius: 50%; background: var(--accent, #e02020); display: inline-block;"></span></span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Mobile Drawer Footer -->
                    <div class="mobile-drawer-footer">
                        <a href="{{ url('/contact') }}" class="mobile-drawer-submit-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                            + Submit Article
                        </a>
                        <div class="mobile-drawer-socials">
                            <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="Youtube">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Search Bar (hidden by default) -->
        <div class="search-bar-drop" id="search-bar-drop" style="display:none;">
            <div class="container">
                <form action="{{ url('/search') }}" method="GET" class="search-bar-form">
                    <input type="text" name="q" placeholder="Search articles, topics..." class="search-bar-input" autofocus>
                    <button type="submit" class="search-bar-btn">Search</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- ================================================================
         FOOTER
         ================================================================ -->
    <footer class="site-footer footer-redesign">
        <div class="container footer-main">

            {{-- Column 1: Logo + tagline --}}
            <div class="footer-col footer-col--brand">
                <a href="{{ url('/') }}" class="footer-logo-link">
                    <img src="{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}" alt="{{ $siteSettings['site_title'] ?? 'TechTV Logo' }}" class="footer-logo-img">
                </a>
                <p class="footer-tagline">Empowering Africa's Digital Frontier with Insights and Analysis.</p>
            </div>

            {{-- Column 2: Categories --}}
            <div class="footer-col">
                <h4 class="footer-col-heading">Categories</h4>
                <div class="footer-recent-posts">
                    <a href="{{ url('/category/technology') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Technology</span>
                    </a>
                    <a href="{{ url('/category/business') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Business</span>
                    </a>
                    <a href="{{ url('/category/economy') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Economy</span>
                    </a>
                    <a href="{{ url('/category/videos') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Techtv TV</span>
                    </a>
                </div>
            </div>

            {{-- Column 3: Trust & Policies --}}
            <div class="footer-col">
                <h4 class="footer-col-heading">Company & Policies</h4>
                <div class="footer-recent-posts">
                    <a href="{{ url('/about') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">About TechTV Network</span>
                    </a>
                    <a href="{{ url('/editorial-policy') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Editorial & Fact-Checking</span>
                    </a>
                    <a href="{{ url('/privacy-policy') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Privacy Policy</span>
                    </a>
                    <a href="{{ url('/terms-of-service') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Terms of Service</span>
                    </a>
                    <a href="{{ url('/cookie-policy') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Cookie Policy</span>
                    </a>
                    <a href="{{ url('/advertise') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Advertise With Us</span>
                    </a>
                    <a href="{{ url('/contact') }}" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">Contact Us</span>
                    </a>
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="footer-post-item" style="padding: 0.35rem 0;">
                        <span class="footer-post-title">XML Sitemap</span>
                    </a>
                </div>
            </div>

            {{-- Column 4: Stay Informed --}}
            <div class="footer-col">
                <h4 class="footer-col-heading">Follow Us</h4>
                <div class="footer-social-grid" style="margin-bottom: 1.25rem;">
                    <a href="https://facebook.com" target="_blank" rel="noopener" class="footer-social-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        Facebook
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="footer-social-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        Twitter
                    </a>
                </div>
                <button onclick="openSubscribeModal(event)" class="footer-inform-btn" style="border-radius: 9999px;">Subscribe Newsletter</button>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom" style="background: #0B193C; border-top: 1px solid rgba(255,255,255,0.08); padding: 1.25rem 0;">
            <div class="container footer-bottom-inner" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <p style="color: #94A3B8; margin: 0; font-size: 0.85rem;">
                    &copy; {{ date('Y') }} TechTV Network. All Rights Reserved. Africa’s Voice for Technology & Business Insight.
                </p>
                <div style="display: flex; gap: 1rem; font-size: 0.82rem; flex-wrap: wrap;">
                    <a href="{{ url('/privacy-policy') }}" style="color: #94A3B8; text-decoration: none;">Privacy Policy</a>
                    <span style="color: #475569;">•</span>
                    <a href="{{ url('/terms-of-service') }}" style="color: #94A3B8; text-decoration: none;">Terms of Service</a>
                    <span style="color: #475569;">•</span>
                    <a href="{{ url('/cookie-policy') }}" style="color: #94A3B8; text-decoration: none;">Cookie Policy</a>
                    <span style="color: #475569;">•</span>
                    <a href="{{ url('/editorial-policy') }}" style="color: #94A3B8; text-decoration: none;">Editorial Standards</a>
                </div>
            </div>
        </div>

        {{-- Scroll to top button --}}
        <button class="scroll-top-btn" id="scroll-top-btn" aria-label="Scroll to top">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 15l-6-6-6 6"/></svg>
        </button>
    </footer>

    <!-- Cookie Consent Banner (Google Consent Mode v2 & GDPR/NDPR) -->
    <div id="cookie-consent-banner" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #0B193C; color: #ffffff; padding: 1.25rem 1.5rem; z-index: 99999; box-shadow: 0 -4px 20px rgba(0,0,0,0.3); border-top: 2px solid var(--accent, #e02020);">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="font-size: 0.88rem; line-height: 1.6; max-width: 800px; color: #cbd5e1;">
                <span style="font-size: 1.1rem; margin-right: 0.35rem;">🍪</span>
                <strong>We Value Your Privacy:</strong> We use cookies and Google AdSense technologies to personalize content, analyze site traffic, and deliver tailored advertising. Learn more in our <a href="{{ url('/privacy-policy') }}" style="color: #38bdf8; text-decoration: underline;">Privacy Policy</a> and <a href="{{ url('/cookie-policy') }}" style="color: #38bdf8; text-decoration: underline;">Cookie Policy</a>.
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-shrink: 0;">
                <button type="button" onclick="acceptAllCookies()" style="background: var(--accent, #e02020); color: #ffffff; border: none; padding: 0.6rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.2s;">
                    Accept All
                </button>
                <button type="button" onclick="acceptEssentialCookies()" style="background: transparent; color: #94a3b8; border: 1px solid #475569; padding: 0.6rem 1.1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: 0.2s;">
                    Essential Only
                </button>
            </div>
        </div>
    </div>

    <!-- Subscribe Modal -->
    <div id="subscribe-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: var(--surface); padding: 2rem; border-radius: 12px; width: 90%; max-width: 400px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <button onclick="closeSubscribeModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            <h3 style="font-family: 'Outfit', sans-serif; margin-bottom: 0.5rem; font-size: 1.5rem;">Subscribe to Daily News</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Get the latest tech and business updates straight to your inbox.</p>
            
            <form id="modal-subscribe-form" style="display: flex; flex-direction: column; gap: 1rem;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.85rem; margin-bottom: 0.25rem;">Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; margin-bottom: 0.25rem;">Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">
                </div>
                <button type="submit" style="margin-top: 0.5rem; width: 100%; padding: 0.85rem; background: var(--accent); color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s;">Subscribe Now</button>
                <div id="modal-subscribe-msg" style="text-align: center; font-size: 0.85rem; margin-top: 0.5rem; display: none;"></div>
            </form>
        </div>
    </div>

    @php
        $sitePopup = $activePopup ?? \App\Models\Popup::where('is_active', true)->first();
    @endphp

    @if($sitePopup)
        @php
            $showPopup = false;
            $currentPath = trim(request()->path(), '/');
            
            if ($sitePopup->display_type === 'all_pages') {
                $showPopup = true;
            } elseif ($sitePopup->display_type === 'specific_page') {
                $targetPath = trim($sitePopup->specific_page_path ?? '', '/ ');
                if ($targetPath === '' || $targetPath === 'home' || $targetPath === 'index' || $targetPath === 'index.php') {
                    $showPopup = (request()->is('/') || $currentPath === '' || $currentPath === 'home' || $currentPath === 'index');
                } else {
                    $showPopup = (request()->is($targetPath) || request()->is($targetPath . '/*') || $currentPath === $targetPath);
                }
            }
        @endphp

        @if($showPopup)
            <!-- Popup Modal -->
            <div id="site-popup-modal" class="popup-modal-overlay">
                <div class="popup-modal-content">
                    <button class="popup-modal-close" onclick="closeSitePopup()">&times;</button>
                    @if($sitePopup->link)
                        <a href="{{ $sitePopup->link }}" target="_blank" rel="noopener noreferrer">
                    @endif
                    <img src="{{ asset($sitePopup->image_path) }}" alt="{{ $sitePopup->name }}" class="popup-modal-img">
                    @if($sitePopup->link)
                        </a>
                    @endif
                </div>
            </div>
            
            <style>
                .popup-modal-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.85);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.3s ease;
                }
                .popup-modal-overlay.show {
                    opacity: 1;
                    pointer-events: auto;
                }
                .popup-modal-content {
                    position: relative;
                    max-width: 90%;
                    max-height: 90vh;
                    background: #ffffff;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 20px 45px rgba(0,0,0,0.5);
                    animation: popupZoomIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                }
                @keyframes popupZoomIn {
                    from { transform: scale(0.92); opacity: 0; }
                    to { transform: scale(1); opacity: 1; }
                }
                .popup-modal-close {
                    position: absolute;
                    top: 10px;
                    right: 12px;
                    background: rgba(0,0,0,0.65);
                    border: 2px solid #ffffff;
                    color: #fff;
                    font-size: 1.5rem;
                    cursor: pointer;
                    line-height: 1;
                    border-radius: 50%;
                    width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                    transition: background 0.2s;
                }
                .popup-modal-close:hover {
                    background: var(--accent, #e02020);
                }
                .popup-modal-img {
                    display: block;
                    max-width: 520px;
                    width: 100%;
                    height: auto;
                    object-fit: contain;
                }
                @media (max-width: 580px) {
                    .popup-modal-img {
                        max-width: 320px;
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check if popup has been closed in this session
                    if (!sessionStorage.getItem('site_popup_closed_' + {{ $sitePopup->id }})) {
                        setTimeout(() => {
                            const modal = document.getElementById('site-popup-modal');
                            if(modal) modal.classList.add('show');
                        }, 1000);
                    }
                });

                function closeSitePopup() {
                    const modal = document.getElementById('site-popup-modal');
                    if(modal) modal.classList.remove('show');
                    sessionStorage.setItem('site_popup_closed_' + {{ $sitePopup->id }}, 'true');
                }
            </script>
        @endif
    @endif

    <!-- Scripts -->
    <script src="{{ asset('assets/js/app.js') }}?v={{ file_exists(public_path('assets/js/app.js')) ? filemtime(public_path('assets/js/app.js')) : time() }}"></script>
    <script>
    // Search toggle
    const searchToggle = document.getElementById('search-toggle');
    const searchDrop = document.getElementById('search-bar-drop');
    if (searchToggle && searchDrop) {
        searchToggle.addEventListener('click', function() {
            searchDrop.style.display = searchDrop.style.display === 'none' ? 'block' : 'none';
            if (searchDrop.style.display === 'block') searchDrop.querySelector('input').focus();
        });
    }

    // Theme persistence
    const saved = localStorage.getItem('techtv-theme');
    if (saved === 'dark') document.documentElement.setAttribute('data-theme', 'dark');

    // Scroll to top
    const scrollBtn = document.getElementById('scroll-top-btn');
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            scrollBtn.classList.toggle('scroll-top-btn--visible', window.scrollY > 400);
        });
        scrollBtn.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    // Modal Subscribe Logic
    const subModal = document.getElementById('subscribe-modal');
    window.openSubscribeModal = function(e) {
        if(e) e.preventDefault();
        subModal.style.display = 'flex';
    };
    window.closeSubscribeModal = function() {
        subModal.style.display = 'none';
        document.getElementById('modal-subscribe-msg').style.display = 'none';
    };
    
    // Close modal on outside click
    subModal.addEventListener('click', function(e) {
        if (e.target === subModal) closeSubscribeModal();
    });

    const modalForm = document.getElementById('modal-subscribe-form');
    if (modalForm) {
        modalForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = this.querySelector('input[name="name"]').value;
            const email = this.querySelector('input[name="email"]').value;
            const btn = this.querySelector('button[type="submit"]');
            const msg = document.getElementById('modal-subscribe-msg');
            
            const origText = btn.textContent;
            btn.textContent = 'Subscribing...';
            btn.disabled = true;
            
            try {
                const res = await fetch(window.siteUrl + '/newsletter/subscribe', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, email })
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    btn.textContent = '✓ Subscribed!';
                    btn.style.background = '#16a34a';
                    msg.style.display = 'block';
                    msg.style.color = '#16a34a';
                    msg.textContent = 'Thank you for subscribing!';
                    this.reset();
                    setTimeout(() => {
                        closeSubscribeModal();
                        btn.textContent = origText;
                        btn.style.background = 'var(--accent)';
                        btn.disabled = false;
                    }, 2000);
                } else {
                    btn.textContent = origText;
                    btn.disabled = false;
                    msg.style.display = 'block';
                    msg.style.color = '#dc2626';
                    msg.textContent = data.message || Object.values(data.errors || {})[0] || 'Error subscribing.';
                }
            } catch(err) { 
                btn.textContent = origText;
                btn.disabled = false;
                msg.style.display = 'block';
                msg.style.color = '#dc2626';
                msg.textContent = 'Network error. Please try again.';
            }
        });
    }

    // Cookie Consent Management (GDPR/NDPR & Google Consent Mode v2)
    (function() {
        const consent = localStorage.getItem('techtv_cookie_consent');
        const banner = document.getElementById('cookie-consent-banner');
        if (!consent && banner) {
            setTimeout(() => {
                banner.style.display = 'block';
            }, 1000);
        }
    })();

    window.acceptAllCookies = function() {
        localStorage.setItem('techtv_cookie_consent', 'all');
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) banner.style.display = 'none';
        if (window.gtag) {
            gtag('consent', 'update', {
                'ad_storage': 'granted',
                'ad_user_data': 'granted',
                'ad_personalization': 'granted',
                'analytics_storage': 'granted'
            });
        }
    };

    window.acceptEssentialCookies = function() {
        localStorage.setItem('techtv_cookie_consent', 'essential');
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) banner.style.display = 'none';
        if (window.gtag) {
            gtag('consent', 'update', {
                'ad_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied',
                'analytics_storage': 'denied'
            });
        }
    };
    </script>
    @yield('scripts')
</body>
</html>
