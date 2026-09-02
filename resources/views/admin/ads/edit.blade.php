@extends('layouts.admin')

@section('header_title', 'Edit Ad Banner')

@section('admin_content')
<div class="admin-form-container" style="max-width: 750px;">
    <form action="{{ url('admin/ads/' . $ad->id) }}" method="POST" enctype="multipart/form-data">
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
            <label class="form-label">Ad Campaign / Client Name *</label>
            <input type="text" name="name" class="input-field" value="{{ old('name', $ad->name) }}" required placeholder="e.g. MTN 5G Header Leaderboard">
        </div>

        <div class="form-group">
            <label class="form-label">Destination Link (Click URL)</label>
            <input type="url" name="link" class="input-field" value="{{ old('link', $ad->link) }}" placeholder="e.g. https://brand.com/promo">
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">Visitors clicking the ad banner will be redirected to this URL.</small>
        </div>

        @php
            $selectedPage = old('page', $ad->page ?? 'home');
            $selectedLocation = old('location', $ad->location);
        @endphp

        <div class="form-group">
            <label class="form-label">Target Page *</label>
            <select name="page" id="ad_page" class="input-field" required onchange="updateSlotOptions()">
                <option value="home" {{ $selectedPage === 'home' ? 'selected' : '' }}>🏠 Homepage</option>
                <option value="post" {{ $selectedPage === 'post' ? 'selected' : '' }}>📝 Single Blog Post</option>
                <option value="category" {{ $selectedPage === 'category' ? 'selected' : '' }}>📁 Category / Archive Page</option>
                <option value="global" {{ $selectedPage === 'global' ? 'selected' : '' }}>🌐 Global (All Pages)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Slot Placement *</label>
            <select name="location" id="ad_location" class="input-field" required onchange="updateSlotHelper()">
                {{-- Injected dynamically by JS based on selected page --}}
            </select>
            <div id="slot-dimension-badge" style="margin-top: 0.5rem; font-size: 0.82rem; color: #1e293b; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 0.45rem 0.75rem; border-radius: 6px; display: inline-block;">
                Recommended Dimensions: <strong id="slot-dimension-text">728×90 px</strong>
            </div>
            <p id="slot-desc-text" style="font-size: 0.8rem; color: var(--admin-text-muted); margin-top: 0.35rem; margin-bottom: 0;"></p>
        </div>

        <!-- Banner Graphic Image -->
        <div class="form-group">
            <label class="form-label" for="image">Ad Banner Graphic *</label>
            <div style="margin-bottom: 1rem; background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem; display: block;" id="preview-container">
                <img id="image-preview" src="{{ asset($ad->image_path) }}" alt="preview" style="max-width: 100%; max-height: 200px; border-radius: 6px; display: block; object-fit: contain;">
            </div>
            
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <input type="file" id="image" name="image" class="input-field" accept="image/*" onchange="previewImage(event)" style="flex: 1 1 200px;">
                <span style="font-size: 0.85rem; color: var(--admin-text-muted); font-weight: 700;">OR</span>
                <button type="button" class="btn-action" onclick="openMediaModal()">📁 Media Library</button>
            </div>
            <input type="hidden" id="image_path" name="image_path" value="{{ $ad->image_path }}">
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
                Supported formats: PNG, JPG, GIF, WebP. High-DPI graphics are automatically adapted.
            </small>
        </div>

        <div class="form-group" style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem 1.25rem;">
            <label style="display: flex; align-items: center; gap: 0.65rem; cursor: pointer; font-weight: 700; color: #1e293b;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--accent);">
                <span>Set as Live / Active Ad in this Slot</span>
            </label>
            <small style="color: var(--admin-text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem; margin-left: 1.8rem;">
                Note: When marked active, this banner will automatically display in its assigned slot on the site.
            </small>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; margin-top: 1.5rem;">
            <a href="{{ url('/admin/ads') }}" class="btn-action" style="padding: 0.75rem 1.5rem; text-decoration: none;">Cancel</a>
            <button class="btn-submit" type="submit" style="padding: 0.75rem 2rem; border: none; cursor: pointer;">
                Update Ad Banner
            </button>
        </div>
    </form>
</div>

<!-- Media Library Modal -->
<div id="mediaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center; padding: 1rem; backdrop-filter: blur(3px);">
    <div style="background: #ffffff; width: 95%; max-width: 820px; height: 85vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; color: #1e293b;">Select Banner from Media Library</h3>
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
const slotsConfig = @json($slotsConfig);
const currentSavedLocation = "{{ $selectedLocation }}";

function updateSlotOptions() {
    const pageSelect = document.getElementById('ad_page');
    const locationSelect = document.getElementById('ad_location');
    const pageKey = pageSelect.value;
    
    locationSelect.innerHTML = '';
    
    if (slotsConfig[pageKey] && slotsConfig[pageKey].slots) {
        const slots = slotsConfig[pageKey].slots;
        for (const [key, info] of Object.entries(slots)) {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = `${info.label} (${info.size})`;
            if (key === currentSavedLocation) {
                opt.selected = true;
            }
            locationSelect.appendChild(opt);
        }
    }
    
    updateSlotHelper();
}

function updateSlotHelper() {
    const pageKey = document.getElementById('ad_page').value;
    const locationKey = document.getElementById('ad_location').value;
    const dimensionText = document.getElementById('slot-dimension-text');
    const descText = document.getElementById('slot-desc-text');
    
    if (slotsConfig[pageKey] && slotsConfig[pageKey].slots[locationKey]) {
        const slot = slotsConfig[pageKey].slots[locationKey];
        dimensionText.textContent = slot.size;
        descText.textContent = slot.desc || '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateSlotOptions();
});

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
    const preview = document.getElementById('image-preview');
    preview.src = url;
    preview.style.display = 'block';
    document.getElementById('preview-container').style.display = 'block';
    document.getElementById('image').value = '';
    closeMediaModal();
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('preview-container').style.display = 'block';
            document.getElementById('image_path').value = '';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
