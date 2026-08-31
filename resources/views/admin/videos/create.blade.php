@extends('layouts.admin')

@section('header_title', 'Add TechTV Video')

@section('admin_content')

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem;">
            <h2 style="font-size: 1.35rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">
                Add TechTV Video
            </h2>
            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
                Add a YouTube video to appear in the Homepage TechTV Video Section.
            </p>
        </div>
        <a href="{{ url('/admin/videos') }}" class="btn-admin-outline" style="font-size: 0.85rem;">
            ← Back to Videos
        </a>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <div style="font-weight: 700; margin-bottom: 0.35rem;">Please correct the errors below:</div>
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
        <form action="{{ url('/admin/videos') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf

            <!-- Video Title -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.88rem; font-weight: 700; color: #334155;">Video Title *</label>
                <input class="input-field" type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Inside Nigeria's Fintech Revolution | TechTV Exclusive" required>
            </div>

            <!-- YouTube Video URL -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.88rem; font-weight: 700; color: #334155;">YouTube Video URL *</label>
                <input class="input-field" type="url" id="video_url_input" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..." required oninput="previewYoutube(this.value)">
                <small style="color: #64748b; font-size: 0.78rem; margin-top: 0.25rem; display: block;">
                    Paste any full YouTube link, short link, or share link. The thumbnail will be automatically extracted!
                </small>
            </div>

            <!-- Live YouTube / Extracted Thumbnail Preview Box -->
            <div id="yt-preview-box" style="display: none; background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1.25rem; align-items: center; gap: 1.25rem;">
                <div style="position: relative; width: 160px; height: 95px; border-radius: 8px; overflow: hidden; background: #0f172a; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                    <img id="yt-preview-img" src="" alt="YouTube Thumbnail Preview" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.25); color: white; font-size: 1.25rem;">
                        ▶
                    </div>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.92rem; color: #166534; display: flex; align-items: center; gap: 0.4rem;">
                        <span>✓</span>
                        <span id="yt-preview-status">YouTube Thumbnail Extracted</span>
                    </div>
                    <p style="color: #64748b; font-size: 0.82rem; margin: 0.25rem 0 0 0;">
                        This high-resolution thumbnail will be automatically attached and displayed on the homepage.
                    </p>
                </div>
            </div>

            <!-- Custom Thumbnail Image (Optional) -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.88rem; font-weight: 700; color: #334155;">Custom Thumbnail Override (Optional)</label>
                <input class="input-field" type="file" name="featured_image" accept="image/*" style="padding: 0.5rem;">
                <small style="color: #64748b; font-size: 0.78rem; margin-top: 0.25rem; display: block;">
                    Leave empty to automatically use the official high-resolution YouTube cover image.
                </small>
            </div>

            <!-- Excerpt / Short Description -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.88rem; font-weight: 700; color: #334155;">Short Description / Summary (Optional)</label>
                <textarea class="input-field" name="excerpt" rows="3" placeholder="Brief summary of the video broadcast...">{{ old('excerpt') }}</textarea>
            </div>

            <!-- Status -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.88rem; font-weight: 700; color: #334155;">Publication Status *</label>
                <select name="status" class="input-field" style="font-weight: 600;">
                    <option value="publish" {{ old('status') === 'publish' ? 'selected' : '' }}>Published (Show on Homepage)</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Save for Later)</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <a href="{{ url('/admin/videos') }}" class="btn-admin-outline">Cancel</a>
                <button type="submit" class="btn-admin-cta" style="padding: 0.75rem 1.5rem;">
                    ✓ Publish Video
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('admin_scripts')
<script>
function extractYoutubeId(url) {
    if (!url) return null;
    const match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
    return match ? match[1] : null;
}

function previewYoutube(url) {
    const box = document.getElementById('yt-preview-box');
    const img = document.getElementById('yt-preview-img');
    const status = document.getElementById('yt-preview-status');
    const ytId = extractYoutubeId(url);
    
    if (ytId) {
        img.src = 'https://img.youtube.com/vi/' + ytId + '/hqdefault.jpg';
        status.textContent = 'YouTube Thumbnail Extracted (ID: ' + ytId + ')';
        box.style.display = 'flex';
    } else {
        box.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('video_url_input');
    if (input && input.value) {
        previewYoutube(input.value);
    }
});
</script>
@endsection
