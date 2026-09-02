@extends('layouts.admin')

@section('header_title', 'Manage Popups')

@section('admin_content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
        <h3 style="font-size: 1.15rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">Promotion Popups</h3>
        <a href="{{ url('admin/popups/create') }}" class="btn-submit" style="padding: 0.55rem 1.25rem; font-size: 0.85rem; text-decoration: none;">+ Add Popup</a>
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
        <table class="table-admin" style="min-width: 680px;">
            <thead>
                <tr>
                    <th style="width: 80px;">Preview</th>
                    <th>Name</th>
                    <th>Display Condition</th>
                    <th>Target Link</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="text-align: right; width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popups as $popup)
                    <tr>
                        <td style="width: 80px;">
                            <img src="{{ asset($popup->image_path) }}" alt="{{ $popup->name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 4px; border: 1px solid var(--border); background: #eee;">
                        </td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $popup->name }}</td>
                        <td>
                            @if($popup->display_type === 'all_pages')
                                <span class="badge-status badge-publish" style="background-color: #3b82f6; border-color: #2563eb;">Global (All Pages)</span>
                            @else
                                <span class="badge-status badge-draft" style="background-color: #f59e0b; border-color: #d97706; color:#fff;">
                                    Path: <code>/{{ ltrim($popup->specific_page_path, '/') }}</code>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($popup->link)
                                <a href="{{ $popup->link }}" target="_blank" style="color: var(--accent); text-decoration: underline; font-size: 0.85rem; word-break: break-all;">
                                    {{ Str::limit($popup->link, 26) }}
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.85rem;">None</span>
                            @endif
                        </td>
                        <td>
                            @if($popup->is_active)
                                <span class="badge-status badge-publish">Active</span>
                            @else
                                <span class="badge-status badge-draft">Inactive</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap; font-size: 0.82rem; color: #64748b;">{{ $popup->created_at->format('M d, Y') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                <a href="{{ url('admin/popups/' . $popup->id . '/edit') }}" class="action-btn action-edit" title="Edit">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </a>
                                <form action="{{ url('admin/popups/' . $popup->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this popup?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-delete" title="Delete" style="background:none; border:none; cursor:pointer;">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                            No popups found. Click "+ Add Popup" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
