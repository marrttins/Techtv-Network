@extends('layouts.admin')

@section('header_title', 'Manage Advertisements')

@section('admin_content')

{{-- ============================================================
     1. STATS & PAGE FILTER TABS
     ============================================================ --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
        <a href="{{ url('/admin/ads?page_filter=all') }}" class="btn-action" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; {{ $currentPage === 'all' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>All Ads</span>
            <span style="background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_all }}</span>
        </a>

        <a href="{{ url('/admin/ads?page_filter=home') }}" class="btn-action" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; {{ $currentPage === 'home' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>🏠 Homepage</span>
            <span style="background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_home }}</span>
        </a>

        <a href="{{ url('/admin/ads?page_filter=post') }}" class="btn-action" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; {{ $currentPage === 'post' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>📝 Post</span>
            <span style="background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_post }}</span>
        </a>

        <a href="{{ url('/admin/ads?page_filter=category') }}" class="btn-action" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; {{ $currentPage === 'category' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>📁 Category</span>
            <span style="background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_category }}</span>
        </a>

        <a href="{{ url('/admin/ads?page_filter=global') }}" class="btn-action" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; {{ $currentPage === 'global' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>🌐 Global</span>
            <span style="background: rgba(255,255,255,0.25); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_global }}</span>
        </a>
    </div>

    <div>
        <a href="{{ url('/admin/ads/create') }}{{ $currentPage !== 'all' ? '?page=' . $currentPage : '' }}" class="btn-submit" style="text-decoration: none; padding: 0.55rem 1.25rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
            <span>+ Add New Ad Banner</span>
        </a>
    </div>
</div>

{{-- ============================================================
     2. ADS TABLE
     ============================================================ --}}
<div class="table-responsive">
    <table class="table-admin" style="min-width: 720px;">
        <thead>
            <tr>
                <th style="width: 130px;">Banner Graphic</th>
                <th style="min-width: 180px;">Campaign & Link</th>
                <th style="width: 140px;">Target Page</th>
                <th>Slot Placement</th>
                <th style="width: 100px;">Status</th>
                <th style="text-align: right; width: 140px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ads as $ad)
                @php
                    $pageConfig = $slotsConfig[$ad->page] ?? null;
                    $slotInfo = $pageConfig['slots'][$ad->location] ?? null;
                @endphp
                <tr>
                    <td>
                        <div style="width: 110px; height: 55px; border-radius: 6px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset($ad->image_path) }}" alt="{{ $ad->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b; font-size: 0.92rem; margin-bottom: 0.2rem;">
                            {{ $ad->name }}
                        </div>
                        @if($ad->link)
                            <a href="{{ $ad->link }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.78rem; color: var(--accent); text-decoration: none; display: inline-flex; align-items: center; gap: 3px; word-break: break-all;">
                                🔗 {{ Str::limit($ad->link, 32) }}
                            </a>
                        @else
                            <span style="font-size: 0.78rem; color: #94a3b8;">No redirect URL</span>
                        @endif
                    </td>
                    <td>
                        @if($ad->page === 'home')
                            <span class="badge-status" style="background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                🏠 Homepage
                            </span>
                        @elseif($ad->page === 'post')
                            <span class="badge-status" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                                📝 Blog Post
                            </span>
                        @elseif($ad->page === 'category')
                            <span class="badge-status" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                📁 Category
                            </span>
                        @else
                            <span class="badge-status" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;">
                                🌐 Global
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 0.85rem; color: #1e293b;">
                            {{ $slotInfo['label'] ?? ucwords(str_replace('_', ' ', $ad->location)) }}
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-family: monospace; background: #f8fafc; padding: 0.1rem 0.35rem; border-radius: 4px; border: 1px solid #e2e8f0; display: inline-block; margin-top: 0.15rem;">
                            {{ $slotInfo['size'] ?? 'Standard Banner' }}
                        </span>
                    </td>
                    <td>
                        @if($ad->is_active)
                            <span class="badge-status" style="background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                                ✓ Live
                            </span>
                        @else
                            <span class="badge-status" style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center;">
                            <a href="{{ url('/admin/ads/' . $ad->id . '/edit') }}" class="btn-action" title="Edit Ad" style="font-size: 0.78rem; padding: 0.35rem 0.65rem;">
                                ✏️ Edit
                            </a>

                            <form action="{{ url('/admin/ads/' . $ad->id) }}" method="POST" onsubmit="return confirm('Permanently delete this ad banner?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete Ad" style="padding: 0.35rem 0.55rem; font-size: 0.78rem;">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        No ad placements configured for this page view. Click <strong>"+ Add New Ad Banner"</strong> to create one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
