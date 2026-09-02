@extends('layouts.admin')

@section('header_title', 'Media Library')

@section('admin_content')

<style>
@media (max-width: 768px) {
    #media-grid {
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)) !important;
        gap: 0.65rem !important;
    }
    .media-card .media-overlay {
        opacity: 0.9 !important;
        background: rgba(11, 25, 60, 0.75) !important;
        top: auto !important;
        bottom: 0 !important;
        height: 38px !important;
        flex-direction: row !important;
        gap: 0.35rem !important;
        padding: 0 0.35rem !important;
    }
    .media-card .media-overlay button {
        width: auto !important;
        flex: 1 !important;
        padding: 0.25rem 0.4rem !important;
        font-size: 0.68rem !important;
        border-radius: 4px !important;
    }
}
</style>

{{-- ================================================================
     TOP BAR
================================================================ --}}
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.5rem;">

    {{-- Filter Tabs + Search --}}
    <form method="GET" action="{{ url('/admin/media') }}" style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; flex: 1 1 100%;">
        {{-- Type Filter --}}
        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 3px; background: var(--surface);">
            @foreach(['all' => 'All ('.$countAll.')', 'images' => '🖼 Images ('.$countImages.')', 'documents' => '📄 Docs ('.$countDocs.')'] as $val => $label)
                <a href="{{ url('/admin/media') }}?type={{ $val }}{{ $search ? '&search='.urlencode($search) : '' }}"
                   style="padding: 0.3rem 0.65rem; border-radius: 5px; font-size: 0.78rem; font-weight: 600; text-decoration: none;
                          background: {{ $type === $val ? 'var(--accent)' : 'transparent' }};
                          color: {{ $type === $val ? '#fff' : 'var(--text-muted)' }};">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Search --}}
        <div style="display: flex; gap: 0.35rem; flex: 1 1 180px; max-width: 100%;">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search files..."
                   class="input-field" style="padding: 0.38rem 0.75rem; font-size: 0.82rem; min-width: 120px;">
            <button type="submit" class="btn-action" style="padding: 0.38rem 0.75rem; font-size: 0.82rem;">🔍</button>
            @if($search)
                <a href="{{ url('/admin/media') }}?type={{ $type }}" class="btn-action" style="color: var(--accent); text-decoration: none; padding: 0.38rem 0.75rem; font-size: 0.82rem;">✕</a>
            @endif
        </div>
    </form>

    {{-- Right: Upload --}}
    <div style="width: 100%; display: flex; justify-content: flex-end;">
        <label for="upload-input" class="btn-submit" style="padding: 0.55rem 1.25rem; cursor: pointer; font-size: 0.85rem; width: auto;">
            + Upload Files
        </label>
    </div>
</div>

{{-- ================================================================
     DRAG & DROP UPLOAD ZONE
================================================================ --}}
<div id="drop-zone"
     style="border: 2px dashed var(--border); border-radius: var(--radius-lg); padding: 1.5rem;
            text-align: center; margin-bottom: 1.5rem; background: var(--surface);
            transition: all 0.2s ease; cursor: pointer; display: none;"
     ondragover="event.preventDefault(); this.style.borderColor='var(--accent)'; this.style.background='rgba(224,32,32,0.05)';"
     ondragleave="this.style.borderColor='var(--border)'; this.style.background='var(--surface)';"
     ondrop="handleDrop(event)"
     onclick="document.getElementById('upload-input').click()">
    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📂</div>
    <p style="font-weight: 700; color: var(--text); margin-bottom: 0.25rem; font-size: 0.95rem;">Drop files here or click to browse</p>
    <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0;">JPEG, PNG, GIF, BMP files will be auto-converted to WebP &bull; Max 50MB per file</p>
</div>

{{-- Hidden file input --}}
<form id="upload-form" action="{{ url('/admin/media/upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" id="upload-input" name="files[]" multiple accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.zip,.csv,.txt,.mp4"
           style="display: none;" onchange="uploadFiles(this.files)">
</form>

{{-- Upload Progress --}}
<div id="upload-progress" style="display: none; margin-bottom: 1.25rem;">
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="font-weight: 700; font-size: 0.88rem;">Uploading files…</span>
            <span id="upload-status" style="font-size: 0.82rem; color: var(--text-muted);">0%</span>
        </div>
        <div style="height: 6px; background: var(--bg-grey); border-radius: 3px; overflow: hidden;">
            <div id="upload-bar" style="height: 100%; width: 0%; background: var(--accent); transition: width 0.3s ease; border-radius: 3px;"></div>
        </div>
    </div>
</div>

{{-- Import Progress --}}
<div id="import-progress" style="display: none; margin-bottom: 1.25rem;">
    <div style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.3); border-radius: var(--radius-md); padding: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div class="spinner" style="width: 18px; height: 18px; border: 2px solid rgba(251,191,36,0.3); border-top-color: #f59e0b; border-radius: 50; animation: spin 0.8s linear infinite;"></div>
            <span style="font-weight: 700; color: #f59e0b; font-size: 0.88rem;" id="import-status-text">Importing WordPress backup files… This may take a minute.</span>
        </div>
    </div>
</div>

{{-- ================================================================
     STATS BAR
================================================================ --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; padding: 0 0.25rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">
            Showing {{ count($files) }} of {{ number_format($total) }} files
            @if($search) matching "<strong style="color: var(--text);">{{ $search }}</strong>"@endif
        </span>
        @if(count($files) > 0)
        <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: var(--text-muted); cursor: pointer; font-weight: 600;">
            <input type="checkbox" id="select-all-cb" onchange="toggleSelectAll(this.checked)"
                   style="width: 15px; height: 15px; cursor: pointer; accent-color: var(--accent);">
            Select All
        </label>
        @endif
    </div>
    <span style="font-size: 0.82rem; color: var(--text-muted);">Page {{ $page }} of {{ $totalPages }}</span>
</div>

{{-- ================================================================
     BULK ACTION BAR (hidden by default)
================================================================ --}}
<div id="bulk-action-bar"
     style="display: none; align-items: center; justify-content: space-between; gap: 1rem;
            background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25);
            border-radius: var(--radius-md); padding: 0.75rem 1.25rem; margin-bottom: 1rem;
            animation: slideDown 0.2s ease;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span id="bulk-count-label" style="font-size: 0.88rem; font-weight: 700; color: var(--accent);">0 selected</span>
        <button onclick="deselectAll()" style="background: none; border: none; color: var(--text-muted); font-size: 0.8rem; cursor: pointer; text-decoration: underline;">Clear</button>
    </div>
    <button id="bulk-delete-btn" onclick="bulkDelete()"
            style="background: var(--accent); color: #fff; border: none; border-radius: var(--radius-sm);
                   padding: 0.5rem 1.25rem; font-weight: 700; font-size: 0.85rem; cursor: pointer;
                   display: flex; align-items: center; gap: 0.4rem; transition: opacity 0.2s;">
        🗑 Delete Selected
    </button>
</div>

{{-- ================================================================
     MEDIA GRID
================================================================ --}}
@if(count($files) === 0)
    <div style="text-align: center; padding: 5rem 2rem; background: var(--surface); border: 1px dashed var(--border); border-radius: var(--radius-lg);">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem;">No files found</h3>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            @if($search)
                No files match "{{ $search }}".
            @else
                Upload files or import the WordPress backup to get started.
            @endif
        </p>
    </div>
@else
    <div id="media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.85rem;">
        @foreach($files as $f)
            <div class="media-card" data-path="{{ $f['path'] }}" data-url="{{ $f['url'] }}"
                 style="position: relative; background: var(--surface); border: 1px solid var(--border);
                        border-radius: var(--radius-md); overflow: hidden; cursor: pointer;
                        transition: border-color 0.15s, box-shadow 0.15s;"
                 onclick="toggleSelect(this, event)">

                {{-- Bulk Checkbox (top-left) --}}
                <div class="media-check" onclick="event.stopPropagation(); toggleSelect(this.closest('.media-card'), null);"
                     style="position: absolute; top: 7px; left: 7px; z-index: 10;
                            width: 20px; height: 20px; background: rgba(0,0,0,0.45); border-radius: 4px;
                            display: flex; align-items: center; justify-content: center;
                            transition: background 0.15s;">
                    <svg id="check-icon" viewBox="0 0 12 10" fill="none" stroke="white" stroke-width="2"
                         style="width: 11px; height: 11px; display: none;"><polyline points="1,5 4,9 11,1"/></svg>
                </div>

                {{-- Thumbnail --}}
                <div style="width: 100%; height: 130px; background: var(--bg-grey); display: flex; align-items: center;
                            justify-content: center; overflow: hidden; position: relative;">
                    @if($f['is_image'])
                        <img src="{{ $f['url'] }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             alt="{{ $f['filename'] }}"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-size: 2.5rem;">🖼</div>
                    @elseif($f['is_svg'])
                        <img src="{{ $f['url'] }}" alt="{{ $f['filename'] }}"
                             style="max-width: 80%; max-height: 80%; object-fit: contain;">
                    @else
                        @php
                            $icons = ['pdf'=>'📄','doc'=>'📝','docx'=>'📝','xls'=>'📊','xlsx'=>'📊',
                                      'zip'=>'🗜','mp4'=>'🎬','mp3'=>'🎵','txt'=>'📃','csv'=>'📊'];
                            $icon = $icons[$f['ext']] ?? '📎';
                        @endphp
                        <div style="font-size: 2.8rem;">{{ $icon }}</div>
                    @endif
                </div>

                {{-- File Info --}}
                <div style="padding: 0.55rem 0.65rem;">
                    <div style="font-size: 0.72rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                         title="{{ $f['filename'] }}">
                        {{ $f['filename'] }}
                    </div>
                    <div style="font-size: 0.67rem; color: var(--text-muted); margin-top: 2px;">
                        {{ App\Http\Controllers\AdminMediaController::humanSize($f['size']) }}
                        &bull; {{ strtoupper($f['ext']) }}
                    </div>
                </div>

                {{-- Hover Overlay (only when NOT in bulk mode) --}}
                <div class="media-overlay"
                     style="position: absolute; inset: 0; background: rgba(0,0,0,0.65); opacity: 0;
                            display: flex; flex-direction: column; align-items: center; justify-content: center;
                            gap: 0.5rem; transition: opacity 0.2s ease;">
                    <button onclick="copyMediaUrl(event, '{{ $f['url'] }}')"
                            style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff;
                                   padding: 0.35rem 0.8rem; border-radius: var(--radius-sm); font-size: 0.75rem;
                                   font-weight: 600; cursor: pointer; width: 110px;">
                        📋 Copy URL
                    </button>
                    <button onclick="deleteMedia(event, '{{ $f['path'] }}', this)"
                            style="background: rgba(239,68,68,0.25); border: 1px solid rgba(239,68,68,0.5); color: #fca5a5;
                                   padding: 0.35rem 0.8rem; border-radius: var(--radius-sm); font-size: 0.75rem;
                                   font-weight: 600; cursor: pointer; width: 110px;">
                        🗑 Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ================================================================
     PAGINATION
================================================================ --}}
@if($totalPages > 1)
    <div style="display: flex; justify-content: center; gap: 0.35rem; margin-top: 2rem; flex-wrap: wrap;">
        @if($page > 1)
            <a href="{{ url('/admin/media') }}?page={{ $page - 1 }}&type={{ $type }}{{ $search ? '&search='.urlencode($search) : '' }}"
               class="btn-action" style="padding: 0.4rem 0.85rem;">‹ Prev</a>
        @endif

        @php
            $start = max(1, $page - 3);
            $end   = min($totalPages, $page + 3);
        @endphp

        @for($p = $start; $p <= $end; $p++)
            <a href="{{ url('/admin/media') }}?page={{ $p }}&type={{ $type }}{{ $search ? '&search='.urlencode($search) : '' }}"
               class="btn-action" style="padding: 0.4rem 0.85rem; {{ $p === $page ? 'background: var(--accent); color: #fff; border-color: var(--accent);' : '' }}">
                {{ $p }}
            </a>
        @endfor

        @if($page < $totalPages)
            <a href="{{ url('/admin/media') }}?page={{ $page + 1 }}&type={{ $type }}{{ $search ? '&search='.urlencode($search) : '' }}"
               class="btn-action" style="padding: 0.4rem 0.85rem;">Next ›</a>
        @endif
    </div>
@endif

{{-- ================================================================
     COPY TOAST
================================================================ --}}
<div id="copy-toast"
     style="position: fixed; bottom: 2rem; right: 2rem; background: #16a34a; color: #fff;
            padding: 0.75rem 1.25rem; border-radius: var(--radius-md); font-weight: 700;
            font-size: 0.88rem; opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease; pointer-events: none; z-index: 9999;">
    ✓ URL copied to clipboard!
</div>

<style>
.media-card:hover .media-overlay { opacity: 1 !important; }
.media-card.selected { border-color: var(--accent) !important; box-shadow: 0 0 0 2px rgba(224,32,32,0.35); }
.media-card.selected .media-check { background: var(--accent) !important; }
.media-card.selected .media-check svg { display: block !important; }
.media-card.selected .media-overlay { display: none !important; }
@keyframes spin { to { transform: rotate(360deg); } }
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
.spinner { animation: spin 0.8s linear infinite; border-radius: 50%; }
</style>

@endsection

@section('admin_scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ---- DRAG & DROP TOGGLE ---- */
const dropZone = document.getElementById('drop-zone');
// Show drop zone always
dropZone.style.display = 'block';

document.addEventListener('dragover', e => e.preventDefault());
document.addEventListener('drop', e => { e.preventDefault(); handleDrop(e); });

function handleDrop(e) {
    const files = e.dataTransfer?.files;
    if (files && files.length) uploadFiles(files);
}

/* ---- UPLOAD ---- */
function uploadFiles(files) {
    if (!files || files.length === 0) return;

    const formData = new FormData();
    formData.append('_token', CSRF);
    for (const f of files) formData.append('files[]', f);

    const progress = document.getElementById('upload-progress');
    const bar      = document.getElementById('upload-bar');
    const status   = document.getElementById('upload-status');
    progress.style.display = 'block';
    bar.style.width = '0%';
    status.textContent = 'Uploading…';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ url("/admin/media/upload") }}');
    xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.upload.onprogress = e => {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            bar.style.width = pct + '%';
            status.textContent = pct + '%';
        }
    };

    xhr.onload = () => {
        if (xhr.status === 200) {
            bar.style.width = '100%';
            bar.style.background = '#16a34a';
            status.textContent = '✓ Done';
            setTimeout(() => location.reload(), 800);
        } else {
            status.textContent = '✗ Upload failed';
            bar.style.background = '#ef4444';
        }
    };

    xhr.onerror = () => { status.textContent = '✗ Network error'; bar.style.background = '#ef4444'; };
    xhr.send(formData);
}

/* ---- COPY URL ---- */
function copyMediaUrl(e, url) {
    e.stopPropagation();
    navigator.clipboard.writeText(url).then(() => {
        const toast = document.getElementById('copy-toast');
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(10px)'; }, 2500);
    }).catch(() => { prompt('Copy this URL:', url); });
}

/* ---- DELETE ---- */
function deleteMedia(e, path, btn) {
    e.stopPropagation();
    if (!confirm('Delete this file permanently?')) return;

    fetch('{{ url("/admin/media/delete") }}', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ path })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const card = btn.closest('.media-card');
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            setTimeout(() => card.remove(), 300);
        } else {
            alert('Error: ' + (data.error || 'Could not delete file.'));
        }
    })
    .catch(() => alert('Network error.'));
}

/* ---- BULK SELECT ---- */
const selectedPaths = new Set();

function toggleSelect(card, e) {
    // If click came from the hover-overlay buttons, don't toggle selection
    if (e && e.target.tagName === 'BUTTON') return;
    // If no items currently selected and overlay is visible, don't toggle
    if (selectedPaths.size === 0 && !card.classList.contains('selected') && e) {
        // Only enter select mode if user clicks the checkbox area
        const check = card.querySelector('.media-check');
        const rect = check.getBoundingClientRect();
        const inCheckbox = e.clientX >= rect.left && e.clientX <= rect.right &&
                           e.clientY >= rect.top  && e.clientY <= rect.bottom;
        if (!inCheckbox) return;
    }

    const path = card.dataset.path;
    if (card.classList.contains('selected')) {
        card.classList.remove('selected');
        selectedPaths.delete(path);
    } else {
        card.classList.add('selected');
        selectedPaths.add(path);
    }
    syncBulkBar();
}

function toggleSelectAll(checked) {
    document.querySelectorAll('.media-card').forEach(card => {
        const path = card.dataset.path;
        if (checked) {
            card.classList.add('selected');
            selectedPaths.add(path);
        } else {
            card.classList.remove('selected');
            selectedPaths.delete(path);
        }
    });
    syncBulkBar();
}

function deselectAll() {
    selectedPaths.clear();
    document.querySelectorAll('.media-card.selected').forEach(c => c.classList.remove('selected'));
    const cb = document.getElementById('select-all-cb');
    if (cb) cb.checked = false;
    syncBulkBar();
}

function syncBulkBar() {
    const bar   = document.getElementById('bulk-action-bar');
    const label = document.getElementById('bulk-count-label');
    const count = selectedPaths.size;

    if (count > 0) {
        bar.style.display = 'flex';
        label.textContent = count + ' file' + (count > 1 ? 's' : '') + ' selected';
    } else {
        bar.style.display = 'none';
    }

    // Sync select-all checkbox state
    const cb    = document.getElementById('select-all-cb');
    const total = document.querySelectorAll('.media-card').length;
    if (cb) cb.checked = (count > 0 && count === total);
}

/* ---- BULK DELETE ---- */
async function bulkDelete() {
    const count = selectedPaths.size;
    if (!count) return;
    if (!confirm(`Permanently delete ${count} file${count > 1 ? 's' : ''}? This cannot be undone.`)) return;

    const btn = document.getElementById('bulk-delete-btn');
    btn.disabled = true;
    btn.textContent = '⏳ Deleting…';

    const paths = Array.from(selectedPaths);
    let deleted = 0, failed = 0;

    for (const path of paths) {
        try {
            const res = await fetch('{{ url("/admin/media/delete") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ path })
            });
            const data = await res.json();
            if (data.success) {
                // Remove card from UI
                const card = document.querySelector(`.media-card[data-path="${CSS.escape(path)}"]`);
                if (card) {
                    card.style.transition = 'all 0.25s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => card.remove(), 250);
                }
                selectedPaths.delete(path);
                deleted++;
            } else { failed++; }
        } catch { failed++; }
    }

    // Show result toast
    const toast = document.getElementById('copy-toast');
    if (deleted > 0) {
        toast.style.background = '#16a34a';
        toast.textContent = `✓ ${deleted} file${deleted > 1 ? 's' : ''} deleted!`;
    } else {
        toast.style.background = '#ef4444';
        toast.textContent = `✗ Failed to delete ${failed} file${failed > 1 ? 's' : ''}.`;
    }
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.background = '#16a34a';
        toast.textContent = '✓ URL copied to clipboard!';
    }, 3000);

    btn.disabled = false;
    btn.textContent = '🗑 Delete Selected';
    syncBulkBar();
}
</script>
@endsection
