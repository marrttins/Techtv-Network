@extends('layouts.admin')

@section('header_title', 'TechTV Videos')

@section('admin_content')

{{-- Top Actions Bar --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem;">
        <h2 style="font-size: 1.35rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">
            TechTV YouTube Videos & Live Stream
        </h2>
        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
            Manage homepage YouTube video feeds and configure the YouTube Live "Watch Now" broadcast.
        </p>
    </div>
    
    <a href="{{ url('/admin/videos/create') }}" class="btn-admin-cta">
        <span>+</span>
        <span>Add New Video</span>
    </a>
</div>

{{-- YouTube Live Stream Management Card --}}
@php
    $curLiveUrl = $liveSettings['youtube_live_url'] ?? '';
    $curLiveTitle = $liveSettings['youtube_live_title'] ?? 'TechTV Live Broadcast';
    $curLiveActive = ($liveSettings['youtube_live_active'] ?? '0') == '1';
    $curLiveYtId = \App\Http\Controllers\AdminVideoController::extractYoutubeId($curLiveUrl);
@endphp
<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; color: #ffffff; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="background: #e02020; color: #fff; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 4px; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 0.35rem;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ffffff; animation: pulse-live 1.5s infinite;"></span>
                HOMEPAGE LIVE BROADCAST
            </span>
            <h3 style="margin: 0; font-size: 1.1rem; font-family: 'Poppins', sans-serif; font-weight: 700; color: #ffffff;">
                YouTube Live "Watch Now" Controller
            </h3>
        </div>
        <div>
            @if($curLiveActive && $curLiveYtId)
                <span style="background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; font-size: 0.8rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                    ● LIVE STREAM ACTIVE & EMBEDDED
                </span>
            @else
                <span style="background: rgba(148, 163, 184, 0.2); border: 1px solid rgba(148, 163, 184, 0.4); color: #cbd5e1; font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                    ○ STANDBY (Shows "NEXT Program Starting Soon")
                </span>
            @endif
        </div>
    </div>

    <form action="{{ url('/admin/videos/live-stream') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1.2fr 1fr auto; gap: 1rem; align-items: end;">
            <div>
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.4rem;">
                    YouTube Live Stream URL / Video Link
                </label>
                <input type="text" name="youtube_live_url" value="{{ old('youtube_live_url', $curLiveUrl) }}" placeholder="https://www.youtube.com/watch?v=... or https://youtube.com/live/..." style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: #ffffff; font-size: 0.88rem; outline: none; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.4rem;">
                    Live Broadcast Title / Program Name
                </label>
                <input type="text" name="youtube_live_title" value="{{ old('youtube_live_title', $curLiveTitle) }}" placeholder="e.g. TechTV Special Live Broadcast" style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: #ffffff; font-size: 0.88rem; outline: none; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; background: rgba(255,255,255,0.08); padding: 0.65rem 1rem; border-radius: 6px; border: 1px solid rgba(255,255,255,0.15); user-select: none;">
                    <input type="checkbox" name="youtube_live_active" value="1" {{ $curLiveActive ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #e02020; cursor: pointer;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #ffffff;">Go Live</span>
                </label>

                <button type="submit" class="btn-admin-cta" style="background: var(--admin-accent, #e02020); border: none; padding: 0.65rem 1.25rem; font-size: 0.88rem; cursor: pointer; white-space: nowrap;">
                    Save Live Settings
                </button>
            </div>
        </div>
        <p style="margin: 0.75rem 0 0 0; font-size: 0.78rem; color: #94a3b8;">
            💡 <strong>How it works:</strong> When checked & an active YouTube URL is provided, the live stream will auto-play on the homepage. When unchecked or empty, visitors will see the broadcast card and clicking "Watch Now" will display <em>"NEXT Program Starting Soon"</em>.
        </p>
    </form>
</div>

{{-- Video Listing Table --}}
<div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
    <table class="table-admin" style="margin: 0; border: none; box-shadow: none;">
        <thead>
            <tr>
                <th style="width: 140px;">Video Preview</th>
                <th>Title & YouTube Link</th>
                <th>Status</th>
                <th>Date Added</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $video)
                @php
                    $thumb = $video->featured_image_url;
                    if (empty($video->featured_image) && $video->video_url) {
                        $ytId = \App\Http\Controllers\AdminVideoController::extractYoutubeId($video->video_url);
                        if ($ytId) {
                            $thumb = "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
                        }
                    }
                @endphp
                <tr>
                    <td style="padding: 1rem;">
                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" style="position: relative; display: block; width: 120px; height: 68px; border-radius: 6px; overflow: hidden; background: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                            <img src="{{ $thumb }}" alt="{{ $video->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://picsum.photos/seed/vid{{ $video->id }}/300/170';">
                            <span style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); color: #ffffff; font-size: 1.2rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.1)'" onmouseout="this.style.background='rgba(0,0,0,0.3)'">
                                ▶
                            </span>
                        </a>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 0.35rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $video->title }}
                        </div>
                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #0284c7; font-size: 0.8rem; text-decoration: none; font-family: monospace;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            <span>📺</span>
                            <span>{{ Str::limit($video->video_url, 45) }}</span>
                            <span>↗</span>
                        </a>
                    </td>
                    <td style="padding: 1rem;">
                        <span class="badge-status badge-{{ $video->status }}">
                            {{ ucfirst($video->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: var(--admin-text-muted); font-size: 0.85rem;">
                        {{ $video->created_at->format('M d, Y') }}
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            <a href="{{ url('/admin/videos/' . $video->id . '/edit') }}" class="btn-action" style="font-size: 0.8rem;">
                                ✏️ Edit
                            </a>
                            <form action="{{ url('/admin/videos/' . $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');" style="margin: 0; display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--admin-text-muted); padding: 3.5rem;">
                        <span style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem;">📹</span>
                        <h4 style="font-size: 1.1rem; color: #1e293b; margin: 0 0 0.35rem 0; font-weight: 700;">No Videos Added Yet</h4>
                        <p style="margin: 0 0 1.25rem 0; font-size: 0.9rem;">Add YouTube videos to showcase them on the homepage video carousel section.</p>
                        <a href="{{ url('/admin/videos/create') }}" class="btn-admin-cta" style="display: inline-flex;">
                            + Add First Video
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($videos->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border); background: #f8fafc;">
            {{ $videos->links() }}
        </div>
    @endif
</div>

@endsection
