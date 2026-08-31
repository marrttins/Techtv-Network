@extends('layouts.admin')

@section('header_title', 'Comment Moderation')

@section('admin_content')

{{-- ============================================================
     1. STATS & FILTER TABS
     ============================================================ --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ url('/admin/comments?status=all') }}" class="btn-action" style="{{ $status === 'all' ? 'background: #0B193C; color: #ffffff; border-color: #0B193C;' : '' }}">
            <span>All Comments</span>
            <span style="background: rgba(255,255,255,0.2); padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem;">{{ $count_all }}</span>
        </a>

        <a href="{{ url('/admin/comments?status=pending') }}" class="btn-action" style="{{ $status === 'pending' ? 'background: #d97706; color: #ffffff; border-color: #d97706;' : '' }}">
            <span>⏳ Pending Review</span>
            <span style="background: {{ $status === 'pending' ? 'rgba(255,255,255,0.25)' : '#fef3c7' }}; color: {{ $status === 'pending' ? '#ffffff' : '#b45309' }}; padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem; font-weight: 700;">{{ $count_pending }}</span>
        </a>

        <a href="{{ url('/admin/comments?status=approved') }}" class="btn-action" style="{{ $status === 'approved' ? 'background: #16a34a; color: #ffffff; border-color: #16a34a;' : '' }}">
            <span>✓ Approved</span>
            <span style="background: {{ $status === 'approved' ? 'rgba(255,255,255,0.25)' : '#dcfce7' }}; color: {{ $status === 'approved' ? '#ffffff' : '#166534' }}; padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem; font-weight: 700;">{{ $count_approved }}</span>
        </a>

        <a href="{{ url('/admin/comments?status=denied') }}" class="btn-action" style="{{ $status === 'denied' ? 'background: #dc2626; color: #ffffff; border-color: #dc2626;' : '' }}">
            <span>✕ Denied</span>
            <span style="background: {{ $status === 'denied' ? 'rgba(255,255,255,0.25)' : '#fee2e2' }}; color: {{ $status === 'denied' ? '#ffffff' : '#991b1b' }}; padding: 0.1rem 0.45rem; border-radius: 9999px; font-size: 0.72rem; margin-left: 0.25rem; font-weight: 700;">{{ $count_denied }}</span>
        </a>
    </div>
</div>

{{-- ============================================================
     2. COMMENTS MODERATION TABLE
     ============================================================ --}}
<div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <table class="table-admin" style="margin: 0; border: none; box-shadow: none;">
        <thead>
            <tr>
                <th style="width: 220px;">Commenter</th>
                <th>Comment Details</th>
                <th style="width: 220px;">Article</th>
                <th style="width: 130px;">Status</th>
                <th style="text-align: right; width: 170px;">Moderation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $c)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #0B193C; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.88rem; flex-shrink: 0;">
                                {{ strtoupper(substr($c->author_name, 0, 1)) }}
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">{{ $c->author_name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $c->author_email }}</div>
                                @if($c->author_url)
                                    <a href="{{ $c->author_url }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.72rem; color: var(--accent); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        🔗 {{ $c->author_url }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.92rem; line-height: 1.5; color: #334155; margin-bottom: 0.35rem;">
                            {!! nl2br(e(strip_tags($c->content))) !!}
                        </div>
                        <span style="font-size: 0.74rem; color: #94a3b8;">
                            Submitted on {{ $c->created_at->format('M d, Y \a\t h:i A') }}
                        </span>
                    </td>
                    <td>
                        @if($c->post)
                            <a href="{{ url('/post/' . $c->post->slug) }}" target="_blank" style="font-weight: 600; font-size: 0.88rem; color: #1e293b; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#1e293b'">
                                {{ $c->post->title }}
                            </a>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem;">[Deleted Article]</span>
                        @endif
                    </td>
                    <td>
                        @if($c->status === 'approved')
                            <span class="badge-status" style="background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                                ✓ Approved
                            </span>
                        @elseif($c->status === 'denied')
                            <span class="badge-status" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                                ✕ Denied
                            </span>
                        @else
                            <span class="badge-status" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                ⏳ Pending
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.4rem; align-items: center;">
                            @if($c->status !== 'approved')
                                <form action="{{ url('/admin/comments/' . $c->id . '/approve') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-action" title="Approve this comment" style="color: #16a34a; border-color: #86efac; background: #f0fdf4;">
                                        ✓ Approve
                                    </button>
                                </form>
                            @endif

                            @if($c->status !== 'denied')
                                <form action="{{ url('/admin/comments/' . $c->id . '/deny') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-action" title="Deny this comment" style="color: #d97706; border-color: #fde68a; background: #fffbeb;">
                                        ✕ Deny
                                    </button>
                                </form>
                            @endif

                            <form action="{{ url('/admin/comments/' . $c->id) }}" method="POST" onsubmit="return confirm('Permanently delete this comment?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete comment" style="padding: 0.4rem 0.55rem;">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        No comments found in this view.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($comments->hasPages())
    <div style="margin-top: 2rem;">
        {{ $comments->appends(['status' => $status])->links() }}
    </div>
@endif

@endsection
