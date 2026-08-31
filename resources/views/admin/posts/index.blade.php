@extends('layouts.admin')

@section('header_title', 'Manage Posts')

@section('admin_content')

    {{-- Filter Header Bar --}}
    <div
        style="background-color: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-soft);">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">

            {{-- Left: Status Filters & Search Form --}}
            <form action="{{ url('/admin/posts') }}" method="GET"
                style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; flex: 1;">
                {{-- Status Tabs --}}
                <a href="{{ url('/admin/posts') }}" class="btn-action {{ !request('status') ? 'active-filter' : '' }}"
                    style="text-decoration: none;">
                    All
                </a>
                <a href="{{ url('/admin/posts?status=publish') }}"
                    class="btn-action {{ request('status') === 'publish' ? 'active-filter' : '' }}"
                    style="text-decoration: none;">
                    Published
                </a>
                <a href="{{ url('/admin/posts?status=draft') }}"
                    class="btn-action {{ request('status') === 'draft' ? 'active-filter' : '' }}"
                    style="text-decoration: none;">
                    Drafts
                </a>

                <div style="height: 24px; width: 1px; background-color: var(--border); margin: 0 0.25rem;"></div>

                {{-- Category Dropdown --}}
                <select name="category_id" class="input-field"
                    style="width: auto; padding: 0.45rem 0.85rem; font-size: 0.85rem;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Search Input --}}
                <div style="display: flex; gap: 0.35rem; flex: 1; max-width: 320px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                        class="input-field" style="padding: 0.45rem 0.85rem; font-size: 0.85rem;">
                    <button type="submit" class="btn-action" style="padding: 0.45rem 0.85rem;">Search</button>
                    @if(request()->hasAny(['search', 'status', 'category_id']))
                        <a href="{{ url('/admin/posts') }}" class="btn-action"
                            style="color: var(--accent); text-decoration: none; padding: 0.45rem 0.85rem;">Clear</a>
                    @endif
                </div>
            </form>

            {{-- Right: New Post Button --}}
            <div>
                <a href="{{ url('/admin/posts/create') }}" class="btn-submit"
                    style="padding: 0.6rem 1.25rem; font-size: 0.88rem; text-decoration: none;">
                    + New Post
                </a>
            </div>
        </div>
    </div>

    {{-- Bulk Actions & Posts Table Form --}}
    <form action="{{ url('/admin/posts/bulk-action') }}" method="POST" id="bulk-action-form">
        @csrf

        {{-- Bulk Action Controls Bar --}}
        <div
            style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <select name="action" id="bulk-action-select" class="input-field"
                    style="width: auto; padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                    <option value="">-- Bulk Actions --</option>
                    <option value="publish">Mark as Published</option>
                    <option value="draft">Mark as Draft</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <button type="submit" class="btn-action" id="apply-bulk-btn"
                    style="padding: 0.4rem 0.9rem; font-weight: 700;">
                    Apply
                </button>

                <span id="selected-count" style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">
                    0 items selected
                </span>
            </div>

            <div style="font-size: 0.85rem; color: var(--text-muted);">
                Showing {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
            </div>
        </div>

        {{-- Table --}}
        <div
            style="overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
            <table class="table-admin" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="select-all" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th style="width: 70px;">Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="post_ids[]" value="{{ $post->id }}" class="post-checkbox"
                                    style="cursor: pointer; width: 16px; height: 16px;">
                            </td>
                            <td>
                                <img src="{{ $post->featured_image_url }}"
                                    onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $post->id }}/80/80';"
                                    alt="thumbnail"
                                    style="width: 52px; height: 52px; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--border);">
                            </td>
                            <td>
                                <a href="{{ url('/admin/posts/' . $post->id . '/edit') }}"
                                    style="font-weight: 700; font-size: 0.92rem; color: var(--text); text-decoration: none; display: block; line-height: 1.35;"
                                    class="post-title-link">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                                <div
                                    style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem; display: flex; gap: 0.75rem; align-items: center;">
                                    <span>Published:
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not Published' }}</span>
                                    <span>&bull;</span>
                                    <a href="{{ url('/post/' . $post->slug) }}" target="_blank"
                                        style="color: var(--text-muted); text-decoration: underline;">View Live ↗</a>
                                </div>
                            </td>
                            <td>
                                @if($post->category)
                                    <span
                                        style="background-color: var(--surface-hover); border: 1px solid var(--border); padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.78rem; font-weight: 600;">
                                        {{ $post->category->name }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Uncategorized</span>
                                @endif
                            </td>
                            <td style="font-size: 0.85rem; font-weight: 500;">
                                {{ $post->author ? $post->author->name : 'TechTV Staff' }}
                            </td>
                            <td style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">
                                👁️ {{ number_format($post->view_count) }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $post->status }}">
                                    {{ strtoupper($post->status) }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                    <a href="{{ url('/admin/posts/' . $post->id . '/edit') }}" class="btn-action"
                                        style="color: var(--accent); border-color: rgba(var(--accent-rgb), 0.2); text-decoration: none;">
                                        Edit
                                    </a>

                                    <button type="button" onclick="deleteSinglePost({{ $post->id }})"
                                        class="btn-action btn-delete">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                No posts found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    {{-- Hidden Form for Single Post Deletion --}}
    <form id="single-delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Pagination Links --}}
    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $posts->links() }}
    </div>

    <style>
        .active-filter {
            background-color: var(--accent) !important;
            color: #ffffff !important;
            border-color: var(--accent) !important;
        }

        .post-title-link:hover {
            color: var(--accent) !important;
        }
    </style>

@endsection

@section('admin_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.post-checkbox');
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkForm = document.getElementById('bulk-action-form');
            const actionSelect = document.getElementById('bulk-action-select');

            function updateCount() {
                const checked = document.querySelectorAll('.post-checkbox:checked');
                const count = checked.length;
                selectedCountSpan.textContent = count + (count === 1 ? ' item selected' : ' items selected');
                if (selectAll) {
                    selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateCount();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCount);
            });

            bulkForm.addEventListener('submit', function (e) {
                const action = actionSelect.value;
                const checked = document.querySelectorAll('.post-checkbox:checked');

                if (!action) {
                    e.preventDefault();
                    alert('Please select a bulk action from the dropdown.');
                    return;
                }

                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one post to apply bulk action.');
                    return;
                }

                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${checked.length} selected post(s)? This action cannot be undone.`)) {
                        e.preventDefault();
                    }
                }
            });
        });

        function deleteSinglePost(id) {
            if (confirm('Are you sure you want to delete this post?')) {
                const form = document.getElementById('single-delete-form');
                form.action = '{{ url("/admin/posts") }}/' + id;
                form.submit();
            }
        }
    </script>
@endsection