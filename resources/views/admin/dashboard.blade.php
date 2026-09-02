@extends('layouts.admin')

@section('header_title', 'Dashboard Overview')

@section('admin_content')

{{-- ============================================================
     1. PRIMARY CONTENT STATS GRID
     ============================================================ --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <!-- Stat: Total Posts -->
    <div class="stat-card-modern">
        <div>
            <span class="stat-card-label">Total Articles</span>
            <h3 class="stat-card-value">{{ number_format($posts_count) }}</h3>
        </div>
        <div class="stat-card-icon-wrap" style="background: #f1f5f9; color: #1e293b;">
            📝
        </div>
    </div>

    <!-- Stat: Categories -->
    <div class="stat-card-modern">
        <div>
            <span class="stat-card-label">Categories</span>
            <h3 class="stat-card-value">{{ number_format($categories_count) }}</h3>
        </div>
        <div class="stat-card-icon-wrap" style="background: #e0f2fe; color: #0369a1;">
            📁
        </div>
    </div>

    <!-- Stat: Approved Comments -->
    <div class="stat-card-modern">
        <div>
            <span class="stat-card-label">Comments</span>
            <h3 class="stat-card-value">{{ number_format($comments_count) }}</h3>
        </div>
        <div class="stat-card-icon-wrap" style="background: #fef3c7; color: #b45309;">
            💬
        </div>
    </div>

    <!-- Stat: Newsletter Subscribers -->
    <div class="stat-card-modern">
        <div>
            <span class="stat-card-label">Subscribers</span>
            <h3 class="stat-card-value">{{ number_format($subscribers_count) }}</h3>
        </div>
        <div class="stat-card-icon-wrap" style="background: #f3e8ff; color: #7e22ce;">
            📧
        </div>
    </div>
</div>

{{-- ============================================================
     2. TRAFFIC & AD IMPRESSION STATS
     ============================================================ --}}
<div style="margin-bottom: 2rem;">
    <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem; margin-bottom: 1rem;">
        <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0; text-transform: uppercase; letter-spacing: 0.04em;">
            Traffic & Ad Impressions
        </h3>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
        <!-- Views Today -->
        <div class="stat-card-modern" style="padding: 1.15rem;">
            <div>
                <span class="stat-card-label" style="font-size: 0.78rem;">Views Today</span>
                <h4 class="stat-card-value" style="font-size: 1.5rem;">{{ number_format($views_today) }}</h4>
            </div>
            <span style="font-size: 1.5rem;">👁️</span>
        </div>

        <!-- Views This Month -->
        <div class="stat-card-modern" style="padding: 1.15rem;">
            <div>
                <span class="stat-card-label" style="font-size: 0.78rem;">Views This Month</span>
                <h4 class="stat-card-value" style="font-size: 1.5rem;">{{ number_format($views_month) }}</h4>
            </div>
            <span style="font-size: 1.5rem;">📈</span>
        </div>

        <!-- Impressions Today -->
        <div class="stat-card-modern" style="padding: 1.15rem;">
            <div>
                <span class="stat-card-label" style="font-size: 0.78rem;">Ad Impressions Today</span>
                <h4 class="stat-card-value" style="font-size: 1.5rem;">{{ number_format($impressions_today) }}</h4>
            </div>
            <span style="font-size: 1.5rem;">✨</span>
        </div>

        <!-- Impressions Weekly -->
        <div class="stat-card-modern" style="padding: 1.15rem;">
            <div>
                <span class="stat-card-label" style="font-size: 0.78rem;">Impressions Weekly</span>
                <h4 class="stat-card-value" style="font-size: 1.5rem;">{{ number_format($impressions_week) }}</h4>
            </div>
            <span style="font-size: 1.5rem;">📊</span>
        </div>

        <!-- Impressions Monthly -->
        <div class="stat-card-modern" style="padding: 1.15rem;">
            <div>
                <span class="stat-card-label" style="font-size: 0.78rem;">Impressions Monthly</span>
                <h4 class="stat-card-value" style="font-size: 1.5rem;">{{ number_format($impressions_month) }}</h4>
            </div>
            <span style="font-size: 1.5rem;">📢</span>
        </div>
    </div>
</div>

{{-- ============================================================
     3. RECENT POSTS & RECENT COMMENTS SPLIT (50/50 on desktop, 1-col on mobile)
     ============================================================ --}}
<div class="admin-grid-2">
    <!-- Recent Posts Widget -->
    <div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 0;">
                Recent Posts
            </h3>
            <a href="{{ url('/admin/posts') }}" class="btn-action" style="font-size: 0.78rem;">View All →</a>
        </div>

        <div class="table-responsive" style="margin-bottom: 0; border: none; box-shadow: none;">
            <table class="table-admin" style="min-width: 320px;">
                <thead>
                    <tr>
                        <th style="padding: 0.65rem 0.85rem; font-size: 0.75rem;">Title</th>
                        <th style="padding: 0.65rem 0.85rem; font-size: 0.75rem; text-align: right;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_posts as $post)
                        <tr>
                            <td style="padding: 0.75rem 0.85rem;">
                                <a href="{{ url('/admin/posts/' . $post->id . '/edit') }}" style="font-weight: 700; color: #1e293b; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#1e293b'">
                                    {{ $post->title }}
                                </a>
                                <span style="font-size: 0.72rem; color: #94a3b8;">{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            </td>
                            <td style="padding: 0.75rem 0.85rem; text-align: right; white-space: nowrap;">
                                <span class="badge-status badge-{{ $post->status }}">
                                    {{ $post->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: #94a3b8; padding: 2rem;">No posts available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Comments Widget -->
    <div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 0;">
                Recent Comments
            </h3>
            <a href="{{ url('/admin/comments') }}" class="btn-action" style="font-size: 0.78rem;">View All →</a>
        </div>

        <div class="table-responsive" style="margin-bottom: 0; border: none; box-shadow: none;">
            <table class="table-admin" style="min-width: 320px;">
                <thead>
                    <tr>
                        <th style="padding: 0.65rem 0.85rem; font-size: 0.75rem;">Author & Comment</th>
                        <th style="padding: 0.65rem 0.85rem; font-size: 0.75rem; text-align: right;">On Post</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_comments as $c)
                        <tr>
                            <td style="padding: 0.75rem 0.85rem;">
                                <span style="font-weight: 700; color: #1e293b; font-size: 0.88rem;">{{ $c->author_name }}</span>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.15rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ strip_tags($c->content) }}
                                </div>
                            </td>
                            <td style="padding: 0.75rem 0.85rem; text-align: right; white-space: nowrap;">
                                <a href="{{ url('/post/' . ($c->post ? $c->post->slug : '')) }}" target="_blank" style="color: #0284c7; font-size: 0.82rem; font-weight: 600; text-decoration: none;">
                                    View →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: #94a3b8; padding: 2rem;">No comments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     4. TOP RANKING ARTICLES (BY VIEWS)
     ============================================================ --}}
<div style="background: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem; margin-bottom: 1.25rem;">
        <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0; text-transform: uppercase; letter-spacing: 0.04em;">
            Top Performing Articles (by Views)
        </h3>
    </div>

    <div class="table-responsive" style="margin-bottom: 0;">
        <table class="table-admin">
            <thead>
                <tr>
                    <th style="width: 60px;">Rank</th>
                    <th style="min-width: 220px;">Article Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($top_posts as $index => $post)
                    <tr>
                        <td>
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: #0B193C; color: #ffffff; font-weight: 800; font-size: 0.78rem;">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <a href="{{ url('/post/' . $post->slug) }}" target="_blank" style="font-weight: 700; text-decoration: none; color: #1e293b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#1e293b'">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td>
                            @if($post->category)
                                <span class="badge-status" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                                    {{ $post->category->name }}
                                </span>
                            @else
                                <span style="color: #94a3b8;">Uncategorized</span>
                            @endif
                        </td>
                        <td style="color: #64748b; font-size: 0.85rem; white-space: nowrap;">{{ $post->author ? $post->author->name : 'TechTV Network' }}</td>
                        <td style="font-weight: 800; color: #1e293b; font-family: 'Poppins', sans-serif; white-space: nowrap;">
                            👁️ {{ number_format($post->view_count) }}
                        </td>
                        <td style="font-size: 0.82rem; color: #94a3b8; white-space: nowrap;">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ url('/admin/posts/' . $post->id . '/edit') }}" class="btn-action" style="font-size: 0.78rem;">✏️ Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 2.5rem;">No performance data recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
