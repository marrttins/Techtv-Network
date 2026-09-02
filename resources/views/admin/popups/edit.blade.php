@extends('layouts.admin')

@section('header_title', 'Edit Popup')

@section('admin_content')
<div class="admin-form-container" style="max-width: 750px;">
    <form action="{{ url('admin/popups/' . $popup->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fca5a5;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Popup Campaign Name *</label>
            <input type="text" name="name" class="input-field" value="{{ old('name', $popup->name) }}" required placeholder="e.g. Special Tech Summit Discount">
        </div>

        <div class="form-group">
            <label class="form-label">Destination Link (Optional)</label>
            <input type="url" name="link" class="input-field" value="{{ old('link', $popup->link) }}" placeholder="e.g. https://techeconomy.ng/register">
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">Clicking the popup image will redirect visitors to this URL.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Display Rule *</label>
            <select name="display_type" id="display_type" class="input-field" required>
                <option value="all_pages" {{ old('display_type', $popup->display_type) == 'all_pages' ? 'selected' : '' }}>🌐 Show on All Pages across the site</option>
                <option value="specific_page" {{ old('display_type', $popup->display_type) == 'specific_page' ? 'selected' : '' }}>🎯 Show only on a specific page</option>
            </select>
        </div>

        {{-- Dynamic Page Selector --}}
        <div class="form-group" id="path-group" style="{{ old('display_type', $popup->display_type) == 'specific_page' ? '' : 'display: none;' }}">
            <label class="form-label">Select Target Page *</label>
            @php
                $currentPath = old('specific_page_path', $popup->specific_page_path);
            @endphp
            <input type="hidden" id="specific_page_path" name="specific_page_path" value="{{ $currentPath ?: '/' }}">
            <select id="specific_page_select" class="input-field" style="margin-bottom: 0.75rem;" onchange="handlePageSelectChange(this)">
                <optgroup label="Main Site Pages">
                    <option value="/" {{ $currentPath === '' || $currentPath === '/' || $currentPath === 'home' ? 'selected' : '' }}>🏠 Home Page (/)</option>
                    <option value="about" {{ $currentPath === 'about' ? 'selected' : '' }}>📄 About Us (/about)</option>
                    <option value="advertise" {{ $currentPath === 'advertise' ? 'selected' : '' }}>📢 Advertise (/advertise)</option>
                    <option value="contact" {{ $currentPath === 'contact' ? 'selected' : '' }}>📞 Contact Us (/contact)</option>
                </optgroup>

                @if(isset($categories) && $categories->count() > 0)
                    <optgroup label="Categories">
                        @foreach($categories as $cat)
                            <option value="category/{{ $cat->slug }}" {{ $currentPath === 'category/' . $cat->slug ? 'selected' : '' }}>
                                📁 Category: {{ $cat->name }} (/category/{{ $cat->slug }})
                            </option>
                        @endforeach
                    </optgroup>
                @endif

                @if(isset($posts) && $posts->count() > 0)
                    <optgroup label="Recent Articles / Posts">
                        @foreach($posts as $pst)
                            <option value="post/{{ $pst->slug }}" {{ $currentPath === 'post/' . $pst->slug ? 'selected' : '' }}>
                                📝 Article: {{ Str::limit($pst->title, 55) }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif

                <optgroup label="Custom Path">
                    <option value="__custom__" {{ !in_array($currentPath, ['', '/', 'home', 'about', 'advertise', 'contact']) && !Str::startsWith($currentPath, ['category/', 'post/']) && !empty($currentPath) ? 'selected' : '' }}>✏️ Enter Custom Page Path...</option>
                </optgroup>
            </select>

            <div id="custom-path-wrapper" style="{{ !in_array($currentPath, ['', '/', 'home', 'about', 'advertise', 'contact']) && !Str::startsWith($currentPath, ['category/', 'post/']) && !empty($currentPath) ? '' : 'display: none;' }}">
                <input type="text" id="custom_page_path_input" class="input-field" value="{{ !in_array($currentPath, ['', '/', 'home', 'about', 'advertise', 'contact']) && !Str::startsWith($currentPath, ['category/', 'post/']) ? $currentPath : '' }}" placeholder="e.g. search or tag/technology" oninput="document.getElementById('specific_page_path').value = this.value">
                <small style="color: var(--admin-text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">Enter the path relative to site root (e.g. <code>search</code> or <code>tag/technology</code>).</small>
            </div>
        </div>

        <!-- Popup Image -->
        <div class="form-group">
            <label class="form-label" for="image">Popup Image Graphic *</label>
            <div style="margin-bottom: 1rem; background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem; display: inline-block;">
                <img id="image-preview" src="{{ asset($popup->image_path) }}" alt="preview" style="max-width: 280px; max-height: 200px; border-radius: 6px; display: block; object-fit: contain;">
            </div>
            
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <input type="file" id="image" name="image" class="input-field" accept="image/*" onchange="previewImage(event)" style="flex: 1 1 200px;">
                <span style="font-size: 0.85rem; color: var(--admin-text-muted); font-weight: 700;">OR</span>
                <button type="button" class="btn-action" onclick="openMediaModal()">📁 Media Library</button>
            </div>
            <input type="hidden" id="image_path" name="image_path" value="{{ $popup->image_path }}">
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
                Recommended: 500×500 px (Square) or 600×400 px (Landscape), PNG/JPG/WebP format.
            </small>
        </div>

        <div class="form-group" style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem 1.25rem;">
            <label style="display: flex; align-items: center; gap: 0.65rem; cursor: pointer; font-weight: 700; color: #1e293b;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $popup->is_active ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--accent);">
                <span>Mark as Active Popup</span>
            </label>
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem; margin-left: 1.8rem;">
                Note: Activating this popup will automatically make it the live popup shown to visitors.
            </small>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; margin-top: 1.5rem;">
            <a href="{{ url('/admin/popups') }}" class="btn-action" style="padding: 0.75rem 1.5rem; text-decoration: none;">Cancel</a>
            <button class="btn-submit" type="submit" style="padding: 0.75rem 2rem; border: none; cursor: pointer;">
                Update Popup
            </button>
        </div>
    </form>
</div>

<!-- Media Library Modal -->
<div id="mediaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center; padding: 1rem; backdrop-filter: blur(3px);">
    <div style="background: #ffffff; width: 95%; max-width: 820px; height: 85vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; color: #1e293b;">Select Image from Media Library</h3>
            <button type="button" onclick="closeMediaModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        
        <div style="padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--admin-border); display: flex; gap: 0.5rem;">
            <input type="text" id="mediaSearch" class="input-field" placeholder="Search media..." style="flex: 1;" onkeyup="if(event.key === 'Enter') loadMedia(1)">
            <button type="button" class="btn-action" onclick="loadMedia(1)">Search</button>
        </div>

        <div id="mediaGrid" style="padding: 1rem; flex: 1; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.75rem; align-content: start;">
            <p style="text-align: center; grid-column: 1 / -1; color: var(--admin-text-muted);">Loading media...</p>
        </div>

        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;" id="mediaPagination">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const displayType = document.getElementById('display_type');
    const pathGroup = document.getElementById('path-group');
    
    function togglePathField() {
        if (displayType.value === 'specific_page') {
            pathGroup.style.display = 'block';
        } else {
            pathGroup.style.display = 'none';
        }
    }
    
    displayType.addEventListener('change', togglePathField);
    togglePathField();
});

function handlePageSelectChange(select) {
    const customWrap = document.getElementById('custom-path-wrapper');
    const pathInput = document.getElementById('specific_page_path');
    
    if (select.value === '__custom__') {
        customWrap.style.display = 'block';
        pathInput.focus();
    } else {
        customWrap.style.display = 'none';
        pathInput.value = select.value;
    }
}

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
    
    grid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1; color: var(--admin-text-muted);">Loading...</p>';
    
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
            if(!data.data || data.data.length === 0) {
                grid.innerHTML = '<p style="text-align: center; grid-column: 1 / -1; color: var(--admin-text-muted);">No images found.</p>';
                pagination.innerHTML = '';
                return;
            }
            
            data.data.forEach(item => {
                const el = document.createElement('div');
                el.style.border = '1px solid #e2e8f0';
                el.style.borderRadius = '8px';
                el.style.padding = '0.5rem';
                el.style.cursor = 'pointer';
                el.style.textAlign = 'center';
                el.style.transition = 'all 0.2s';
                el.onmouseover = () => el.style.borderColor = 'var(--accent)';
                el.onmouseout = () => el.style.borderColor = '#e2e8f0';
                
                el.innerHTML = `
                    <img src="${item.url}" style="width: 100%; height: 90px; object-fit: cover; border-radius: 4px; margin-bottom: 0.4rem;">
                    <div style="font-size: 0.72rem; color: #475569; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${item.name}</div>
                `;
                el.onclick = () => selectMedia(item.url);
                grid.appendChild(el);
            });
            
            pagination.innerHTML = `
                <button type="button" class="btn-action" ${data.prev_page_url ? '' : 'disabled'} onclick="loadMedia(${page - 1})">Previous</button>
                <span style="font-size: 0.85rem; color: #64748b;">Page ${data.current_page} of ${data.last_page}</span>
                <button type="button" class="btn-action" ${data.next_page_url ? '' : 'disabled'} onclick="loadMedia(${page + 1})">Next</button>
            `;
        });
}

function selectMedia(url) {
    document.getElementById('image_path').value = url;
    document.getElementById('image-preview').src = url;
    document.getElementById('image').value = '';
    closeMediaModal();
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image_path').value = '';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
