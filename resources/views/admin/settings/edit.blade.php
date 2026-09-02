@extends('layouts.admin')

@section('header_title', 'Site Settings')

@section('admin_content')
<div class="admin-form-container" style="max-width: 650px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
        <h3 style="font-size: 1.15rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">Global Customizations</h3>
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

    <form action="{{ url('admin/settings') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf

        <!-- Site Title -->
        <div class="form-group">
            <label class="form-label">Site Title</label>
            <input type="text" name="site_title" class="input-field" value="{{ old('site_title', $settings['site_title'] ?? 'TechTv Network') }}" required placeholder="e.g. TechTv Network">
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Used as the main title tag fallback across pages and HTML meta parameters.</p>
        </div>

        <!-- Site Logo -->
        <div class="form-group">
            <label class="form-label" for="logo">Site Logo Image</label>
            <div style="margin-bottom: 0.75rem;">
                @php 
                    $logoUrl = isset($settings['site_logo']) ? asset($settings['site_logo']) : asset('assets/img/logo.jpg');
                @endphp
                <img id="logo-preview" src="{{ $logoUrl }}" alt="site logo preview" style="max-width: 180px; height: auto; border-radius: var(--radius-sm); border: 1px solid var(--border); display: block; background: #fff; padding: 5px;">
            </div>
            
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <input type="file" id="logo" name="logo" class="input-field" accept="image/*" onchange="previewImage(event)" style="flex: 1 1 180px;">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">OR</span>
                <button type="button" class="btn-action" onclick="openMediaModal()">Select from Media</button>
            </div>
            <input type="hidden" id="logo_path" name="logo_path" value="{{ $settings['site_logo'] ?? '' }}">
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.4rem;">
                Recommended aspect ratio: Rectangular brand banner (e.g. 180 x 60 px).
            </p>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 1.25rem;">
            <a href="{{ url('admin') }}" class="btn-action" style="text-decoration: none; padding: 0.75rem 1.5rem;">Cancel</a>
            <button type="submit" class="btn-submit" style="padding: 0.75rem 2rem;">Save Settings</button>
        </div>
    </form>
</div>

<!-- Media Library Modal (Reusable) -->
<div id="mediaModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 99999; align-items: center; justify-content: center; padding: 1rem; backdrop-filter: blur(3px);">
    <div style="background: var(--surface); padding: 1.25rem; border-radius: 12px; width: 95%; max-width: 750px; height: 85vh; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; margin: 0;">Select from Media Library</h3>
            <button type="button" onclick="closeMediaModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <div style="margin-bottom: 0.75rem; display: flex; gap: 0.5rem;">
            <input type="text" id="mediaSearch" placeholder="Search media..." class="input-field" style="flex: 1; padding: 0.45rem 0.85rem; font-size: 0.85rem;">
            <button type="button" class="btn-action" onclick="loadMedia(1)">Search</button>
        </div>
        <div id="mediaGrid" style="flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.75rem; overflow-y: auto; padding-right: 0.25rem;">
            Loading media...
        </div>
        <div id="mediaPagination" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 0.75rem;">
            <!-- Buttons injected via JS -->
        </div>
    </div>
</div>

<script>
let currentMediaPage = 1;

function openMediaModal() {
    document.getElementById('mediaModal').style.display = 'flex';
    if(document.getElementById('mediaGrid').innerHTML.includes('Loading media...')) {
        loadMedia(1);
    }
}

function closeMediaModal() {
    document.getElementById('mediaModal').style.display = 'none';
}

function loadMedia(page) {
    const search = document.getElementById('mediaSearch').value;
    currentMediaPage = page;
    const grid = document.getElementById('mediaGrid');
    const pagination = document.getElementById('mediaPagination');
    
    grid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">Loading...</p>';
    
    fetch(`{{ url('admin/media/api') }}?page=${page}&search=${encodeURIComponent(search)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = '';
            if(data.images.length === 0) {
                grid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">No images found.</p>';
                pagination.innerHTML = '';
                return;
            }
            
            data.images.forEach(img => {
                const div = document.createElement('div');
                div.style.cursor = 'pointer';
                div.style.border = '2px solid transparent';
                div.style.borderRadius = 'var(--radius-sm)';
                div.style.overflow = 'hidden';
                div.onclick = function() {
                    selectMediaImage(img.path, img.url);
                };
                div.innerHTML = `<img src="${img.url}" style="width: 100%; height: 100px; object-fit: cover; display: block;" title="${img.filename}">`;
                grid.appendChild(div);
            });
            
            let html = '';
            if (data.page > 1) {
                html += `<button type="button" class="btn-action" onclick="loadMedia(${data.page - 1})">&laquo; Prev</button>`;
            } else {
                html += `<div></div>`;
            }
            html += `<span style="font-size:0.9rem; color:var(--text-muted);">Page ${data.page} of ${data.totalPages}</span>`;
            if (data.page < data.totalPages) {
                html += `<button type="button" class="btn-action" onclick="loadMedia(${data.page + 1})">Next &raquo;</button>`;
            } else {
                html += `<div></div>`;
            }
            pagination.innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            grid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1; color: red;">Failed to load media.</p>';
        });
}

function selectMediaImage(path, url) {
    document.getElementById('logo').value = '';
    document.getElementById('logo_path').value = path;
    const preview = document.getElementById('logo-preview');
    preview.src = url;
    preview.style.display = 'block';
    closeMediaModal();
}

function previewImage(event) {
    document.getElementById('logo_path').value = '';
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('logo-preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection
