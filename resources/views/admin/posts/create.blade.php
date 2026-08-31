@extends('layouts.admin')

@section('header_title', 'Create New Post')

@section('admin_content')
<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
.ck-editor__editable_inline {
    min-height: 250px;
}
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
      ClassicEditor
          .create( document.querySelector( '#body' ) )
          .catch( error => {
              console.error( error );
          } );
  });
</script>

<div style="background-color: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-soft); max-width: 900px; margin: 0 auto;">
    <form action="{{ url('/admin/posts') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        
        <!-- Title -->
        <div class="form-group">
            <label class="form-label" for="title">Post Title</label>
            <input class="input-field" type="text" id="title" name="title" placeholder="Enter article title..." required>
        </div>

        <!-- Excerpt -->
        <div class="form-group">
            <label class="form-label" for="excerpt">Excerpt / Summary</label>
            <textarea class="input-field" id="excerpt" name="excerpt" rows="3" placeholder="Brief summary of the article..."></textarea>
        </div>

        <!-- Body Content -->
        <div class="form-group">
            <label class="form-label" for="body">Content Body</label>
            <textarea class="input-field" id="body" name="body" rows="12" placeholder="Write article content here..." required></textarea>
        </div>

        <!-- Two Column Grid for Metadata -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Category -->
            <div class="form-group">
                <label class="form-label" for="category_id">Category</label>
                <select class="input-field" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select class="input-field" id="status" name="status" required>
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
            </div>
            
            @if(auth()->user() && auth()->user()->role === 'super-admin')
            <!-- Author -->
            <div class="form-group">
                <label class="form-label" for="author_id">Author (Super Admin)</label>
                <select class="input-field" id="author_id" name="author_id">
                    @foreach($authors as $authorUser)
                        <option value="{{ $authorUser->id }}" {{ auth()->id() == $authorUser->id ? 'selected' : '' }}>{{ $authorUser->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Publish Date -->
            <div class="form-group">
                <label class="form-label" for="published_at">Publish Date (Schedule/Backdate)</label>
                <input type="datetime-local" class="input-field" id="published_at" name="published_at" value="{{ now()->format('Y-m-d\TH:i') }}">
            </div>
        </div>

        <!-- Featured Image -->
        <div class="form-group">
            <label class="form-label" for="featured_image">Featured Image</label>
            <div style="margin-bottom: 0.75rem;">
                <img id="image-preview" src="" alt="preview" style="max-width: 150px; height: auto; border-radius: var(--radius-sm); border: 1px solid var(--border); display: none;">
            </div>
            
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input type="file" id="featured_image" name="featured_image" class="input-field" accept="image/*" onchange="previewImage(event)" style="flex: 1;">
                <span style="font-size: 0.9rem; color: var(--text-muted);">OR</span>
                <button type="button" class="btn-action" onclick="openMediaModal()">Select from Media</button>
            </div>
            <input type="hidden" id="featured_image_path" name="featured_image_path" value="">
        </div>

        <!-- YouTube Video URL (Optional) -->
        <div class="form-group">
            <label class="form-label" for="video_url">YouTube Video URL (Optional — For TechTV Video Section)</label>
            <input type="url" class="input-field" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">
                When users click this video post in the TechTV YouTube section, they will be taken directly to this video on YouTube.
            </small>
        </div>

        <!-- Tags / SEO Keywords (Max 5) -->
        <div class="form-group" style="background: var(--background); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                <label class="form-label" style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 0.4rem;">
                    <span>🏷️ Tags & SEO Keywords</span>
                    <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted);">(Max 5 keywords)</span>
                </label>
                <span id="tagCountBadge" style="background: #e2e8f0; color: #475569; font-size: 0.75rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 9999px;">
                    0 / 5 Tags
                </span>
            </div>
            
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 0.85rem 0;">
                Type keywords and press <kbd style="background: #f1f5f9; padding: 1px 5px; border-radius: 3px; border: 1px solid #cbd5e1; font-family: monospace;">Enter</kbd> or <kbd style="background: #f1f5f9; padding: 1px 5px; border-radius: 3px; border: 1px solid #cbd5e1; font-family: monospace;">Comma ,</kbd> to add. These tags enhance article search visibility, topic indexing, and SEO.
            </p>

            <!-- Tags Input Container -->
            <div id="tagsInputBox" style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-sm); padding: 0.5rem 0.75rem; min-height: 48px; cursor: text;" onclick="document.getElementById('tagTextInput').focus()">
                <!-- Dynamic Tag Chips injected via JS -->
                <input type="text" id="tagTextInput" placeholder="Type tag / SEO keyword and press Enter..." style="border: none; outline: none; background: transparent; font-size: 0.9rem; color: var(--text); flex: 1; min-width: 180px; padding: 0.25rem 0;">
            </div>

            <!-- Hidden input that submits the comma-separated string -->
            <input type="hidden" name="tags_input" id="tags_input_hidden" value="{{ old('tags_input', '') }}">

            <!-- Popular / Existing Tags Click to Add -->
            @if(isset($tags) && $tags->count() > 0)
                <div style="margin-top: 0.85rem;">
                    <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">
                        Quick Add from Existing Topics:
                    </span>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; max-height: 90px; overflow-y: auto;">
                        @foreach($tags as $t)
                            <button type="button" class="btn-tag-suggestion" onclick="addTag('{{ addslashes($t->name) }}')" style="background: var(--surface); border: 1px solid var(--border); padding: 0.2rem 0.55rem; border-radius: 4px; font-size: 0.78rem; color: var(--text-secondary); cursor: pointer; transition: all 0.15s;" onmouseover="this.style.borderColor='var(--accent)'; this.style.color='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)'">
                                + {{ $t->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
            <a href="{{ url('/admin/posts') }}" class="btn-action" style="padding: 0.85rem 1.5rem;">Cancel</a>
            <button class="btn-submit" type="submit" style="padding: 0.85rem 2rem;">Save Post</button>
        </div>
    </form>
</div>

<!-- Media Modal -->
<div id="mediaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); width: 80%; max-width: 800px; height: 80%; border-radius: var(--radius-lg); display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-strong);">
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: 'Outfit';">Select Featured Image</h3>
            <button type="button" onclick="closeMediaModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        
        <!-- Search Bar -->
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; gap: 1rem;">
            <input type="text" id="mediaSearch" class="input-field" placeholder="Search media..." style="flex: 1;" onkeyup="if(event.key === 'Enter') loadMedia(1)">
            <button type="button" class="btn-action" onclick="loadMedia(1)">Search</button>
        </div>

        <div id="mediaGrid" style="padding: 1.5rem; flex: 1; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; align-content: start;">
            <!-- Images loaded via JS -->
            <p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">Loading media...</p>
        </div>

        <!-- Pagination -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;" id="mediaPagination">
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
    document.getElementById('featured_image').value = '';
    document.getElementById('featured_image_path').value = path;
    const preview = document.getElementById('image-preview');
    preview.src = url;
    preview.style.display = 'block';
    closeMediaModal();
}

function previewImage(event) {
    document.getElementById('featured_image_path').value = '';
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('image-preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

// ----------------------------------------------------
// Tag & SEO Keyword Management (Max 5)
// ----------------------------------------------------
let currentTags = [];

function initTags() {
    const hiddenInput = document.getElementById('tags_input_hidden');
    if (hiddenInput && hiddenInput.value) {
        const raw = hiddenInput.value.split(',');
        currentTags = raw.map(t => t.trim()).filter(t => t.length > 0).slice(0, 5);
    } else {
        currentTags = [];
    }
    renderTags();
}

function renderTags() {
    const container = document.getElementById('tagsInputBox');
    const input = document.getElementById('tagTextInput');
    const hiddenInput = document.getElementById('tags_input_hidden');
    const badge = document.getElementById('tagCountBadge');
    
    if (!container || !input) return;
    
    // Remove existing tag chips
    container.querySelectorAll('.tag-chip').forEach(el => el.remove());
    
    currentTags.forEach((tag, index) => {
        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.style.cssText = 'background: #0B193C; color: #ffffff; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;';
        
        const removeBtn = document.createElement('span');
        removeBtn.innerHTML = '&times;';
        removeBtn.style.cssText = 'cursor: pointer; opacity: 0.7; font-weight: bold; font-size: 1rem; line-height: 1;';
        removeBtn.onmouseover = () => removeBtn.style.opacity = '1';
        removeBtn.onmouseout = () => removeBtn.style.opacity = '0.7';
        removeBtn.onclick = (e) => {
            e.stopPropagation();
            removeTag(index);
        };
        
        chip.textContent = '#' + tag + ' ';
        chip.appendChild(removeBtn);
        container.insertBefore(chip, input);
    });
    
    if (hiddenInput) {
        hiddenInput.value = currentTags.join(',');
    }
    
    if (badge) {
        badge.textContent = `${currentTags.length} / 5 Tags`;
        if (currentTags.length >= 5) {
            badge.style.background = '#fee2e2';
            badge.style.color = '#b91c1c';
            input.placeholder = 'Max 5 keywords reached';
            input.disabled = true;
        } else {
            badge.style.background = '#e2e8f0';
            badge.style.color = '#475569';
            input.placeholder = 'Type tag and press Enter or comma...';
            input.disabled = false;
        }
    }
}

function addTag(name) {
    name = (name || '').trim().replace(/^#/, '');
    if (!name) return;
    if (currentTags.length >= 5) {
        alert('You can add a maximum of 5 tags / SEO keywords.');
        return;
    }
    const exists = currentTags.some(t => t.toLowerCase() === name.toLowerCase());
    if (!exists) {
        currentTags.push(name);
        renderTags();
    }
    const input = document.getElementById('tagTextInput');
    if (input) {
        input.value = '';
    }
}

function removeTag(index) {
    currentTags.splice(index, 1);
    renderTags();
    const input = document.getElementById('tagTextInput');
    if (input) input.focus();
}

document.addEventListener('DOMContentLoaded', function() {
    initTags();
    
    const input = document.getElementById('tagTextInput');
    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTag(this.value);
            } else if (e.key === 'Backspace' && this.value === '' && currentTags.length > 0) {
                removeTag(currentTags.length - 1);
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.value.trim() && currentTags.length < 5) {
                addTag(this.value);
            }
        });
    }
});
</script>
@endsection
