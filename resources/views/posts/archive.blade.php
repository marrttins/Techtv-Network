@extends('layouts.layout')

@section('title', $title . ' | TechTV Network')

@section('content')

{{-- ============================================================
     1. CATEGORY / ARCHIVE HERO BANNER
     ============================================================ --}}
<section class="archive-hero-section">
    <div class="container">
        <div class="archive-hero-inner">
            <div class="archive-hero-badge">
                <span class="badge-pill-category">{{ strtoupper(str_replace(['Category: ', 'Tag: '], '', $title)) }}</span>
            </div>
            <h1 class="archive-hero-title">{{ $title }}</h1>
            <p class="archive-hero-subtitle">{{ $subtitle }}</p>
            @if(isset($posts) && method_exists($posts, 'total'))
                <span class="archive-hero-count">{{ $posts->total() }} Articles Published</span>
            @endif
        </div>
    </div>
</section>

{{-- ============================================================
     2. TOP LEADERBOARD AD BANNER (728×90 / 970×150)
     ============================================================ --}}
@php
    $adCatTop = \App\Models\Ad::getSlot('category_header_leaderboard', 'category') 
        ?? \App\Models\Ad::getSlot('global_header_leaderboard', 'global');
@endphp
<div class="container" style="padding-top: 2rem;">
    <div class="ad-banner-section" style="padding: 0 0 2rem;">
        <div class="ad-banner-box">
            <span class="ad-banner-label">ADVERTISEMENT</span>
            <div class="ad-banner-content">
                @if($adCatTop)
                    @if($adCatTop->link)
                        <a href="{{ $adCatTop->link }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($adCatTop->image_path) }}" alt="{{ $adCatTop->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                        </a>
                    @else
                        <img src="{{ asset($adCatTop->image_path) }}" alt="{{ $adCatTop->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                    @endif
                @else
                    <div class="ad-placeholder">
                        <span>Header Leaderboard Ad Space — 728×90 / 970×150</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     3. MAIN ARCHIVE LAYOUT (MAIN 70% + SIDEBAR 30%)
     ============================================================ --}}
<div class="container" style="padding-bottom: 4rem;">
    <div class="archive-layout-grid">

        {{-- ========== LEFT COLUMN: MAIN CONTENT ========== --}}
        <main class="archive-main">

            @if($posts->count() > 0)
                {{-- Highlighted Top Post in Category --}}
                @php $firstPost = $posts->first(); @endphp
                @if($firstPost)
                    <div class="archive-lead-card">
                        <a href="{{ url('/post/' . $firstPost->slug) }}" class="archive-lead-img-wrap">
                            <img src="{{ $firstPost->featured_image_url }}"
                                onerror="this.onerror=null; this.src='https://picsum.photos/seed/lead{{ $firstPost->id }}/800/450';"
                                alt="{{ $firstPost->title }}">
                            @if($firstPost->category)
                                <span class="badge-pill-category archive-lead-badge">{{ $firstPost->category->name }}</span>
                            @endif
                        </a>
                        <div class="archive-lead-body">
                            <h2 class="archive-lead-title">
                                <a href="{{ url('/post/' . $firstPost->slug) }}">{{ $firstPost->title }}</a>
                            </h2>
                            <p class="archive-lead-excerpt">
                                {{ Str::limit(strip_tags($firstPost->excerpt ?: $firstPost->body), 140) }}
                            </p>
                            <div class="archive-lead-meta">
                                <span>By {{ $firstPost->author ? $firstPost->author->name : 'TechTV Network' }}</span>
                                <span>•</span>
                                <span>{{ $firstPost->published_at ? $firstPost->published_at->format('M j, Y') : $firstPost->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Section Header --}}
                <div class="category-block-header" style="margin-top: 2rem; margin-bottom: 1.5rem;">
                    <h3 class="category-block-title">Recent Stories</h3>
                </div>

                {{-- Post Cards Grid --}}
                <div class="archive-posts-grid">
                    @foreach($posts->skip(1)->take(4) as $post)
                        <div class="category-card-col">
                            <a href="{{ url('/post/' . $post->slug) }}" class="category-card-col-img">
                                <img src="{{ $post->featured_image_url }}"
                                    onerror="this.onerror=null; this.src='https://picsum.photos/seed/arch{{ $post->id }}/500/300';"
                                    alt="{{ $post->title }}" loading="lazy">
                            </a>
                            <div class="category-card-col-body">
                                <h4 class="category-card-col-title">
                                    <a href="{{ url('/post/' . $post->slug) }}">{{ $post->title }}</a>
                                </h4>
                                <p class="category-card-col-excerpt" style="font-size: 0.82rem; color: #64748b; margin-top: 0.4rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($post->excerpt ?: $post->body), 90) }}
                                </p>
                                <div class="category-card-col-meta" style="font-size: 0.72rem; color: #94a3b8; display: flex; align-items: center; gap: 0.4rem; margin-top: auto; padding-top: 0.5rem;">
                                    <span>{{ $post->published_at ? $post->published_at->format('M j, Y') : $post->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- IN-FEED LEADERBOARD AD SLOT (728×90) --}}
                @php
                    $adCatInFeed = \App\Models\Ad::getSlot('category_in_feed', 'category');
                @endphp
                <div class="ad-banner-section" style="padding: 2rem 0;">
                    <div class="ad-banner-box">
                        <span class="ad-banner-label">ADVERTISEMENT</span>
                        <div class="ad-banner-content">
                            @if($adCatInFeed)
                                @if($adCatInFeed->link)
                                    <a href="{{ $adCatInFeed->link }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset($adCatInFeed->image_path) }}" alt="{{ $adCatInFeed->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                                    </a>
                                @else
                                    <img src="{{ asset($adCatInFeed->image_path) }}" alt="{{ $adCatInFeed->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                                @endif
                            @else
                                <div class="ad-placeholder" style="min-height: 90px;">
                                    <span>In-Feed Sponsor Ad — 728×90</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Remaining Posts Grid --}}
                @if($posts->count() > 5)
                    <div class="archive-posts-grid">
                        @foreach($posts->skip(5) as $post)
                            <div class="category-card-col">
                                <a href="{{ url('/post/' . $post->slug) }}" class="category-card-col-img">
                                    <img src="{{ $post->featured_image_url }}"
                                        onerror="this.onerror=null; this.src='https://picsum.photos/seed/arch{{ $post->id }}/500/300';"
                                        alt="{{ $post->title }}" loading="lazy">
                                </a>
                                <div class="category-card-col-body">
                                    <h4 class="category-card-col-title">
                                        <a href="{{ url('/post/' . $post->slug) }}">{{ $post->title }}</a>
                                    </h4>
                                    <p class="category-card-col-excerpt" style="font-size: 0.82rem; color: #64748b; margin-top: 0.4rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($post->excerpt ?: $post->body), 90) }}
                                    </p>
                                    <div class="category-card-col-meta" style="font-size: 0.72rem; color: #94a3b8; display: flex; align-items: center; gap: 0.4rem; margin-top: auto; padding-top: 0.5rem;">
                                        <span>{{ $post->published_at ? $post->published_at->format('M j, Y') : $post->created_at->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Pagination --}}
                @if(method_exists($posts, 'links'))
                    <div class="pagination-wrap" style="margin-top: 2.5rem;">
                        {{ $posts->links() }}
                    </div>
                @endif

            @else
                <div style="text-align: center; padding: 4rem 1.5rem; background: #ffffff; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 3rem; display: block; margin-bottom: 1rem;">📰</span>
                    <h3 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">No stories found yet</h3>
                    <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;">There are no published articles currently available in this category.</p>
                    <a href="{{ url('/') }}" class="btn-action" style="background: var(--accent); color: #ffffff; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 9999px; font-weight: 600; display: inline-block;">Return to Homepage</a>
                </div>
            @endif

        </main>

        {{-- ========== RIGHT COLUMN: SIDEBAR (340px) ========== --}}
        <aside class="archive-sidebar">

            {{-- 1. MEDIUM RECTANGLE AD SLOT (300×250) --}}
            @php
                $adCatSidebarRect = \App\Models\Ad::getSlot('category_sidebar_rect', 'category')
                    ?? \App\Models\Ad::getSlot('home_sidebar_rect', 'home');
            @endphp
            <div class="sidebar-widget" style="margin-bottom: 2rem;">
                <div class="ad-banner-box" style="padding: 1.25rem 1rem;">
                    <span class="ad-banner-label">SPONSORED</span>
                    <div class="ad-content" style="display: flex; align-items: center; justify-content: center;">
                        @if($adCatSidebarRect)
                            @if($adCatSidebarRect->link)
                                <a href="{{ $adCatSidebarRect->link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($adCatSidebarRect->image_path) }}" alt="{{ $adCatSidebarRect->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                </a>
                            @else
                                <img src="{{ asset($adCatSidebarRect->image_path) }}" alt="{{ $adCatSidebarRect->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                            @endif
                        @else
                            <div class="ad-placeholder" style="min-height: 250px; width: 100%;">
                                <span>Medium Rectangle — 300×250</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. TRENDING / MOST READ POSTS (HOMEPAGE STYLE) --}}
            @if(isset($trending_posts) && $trending_posts->count() > 0)
                <div class="sidebar-widget" style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="border-left: 3px solid var(--accent); padding-left: 0.75rem; margin-bottom: 1.25rem;">
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: var(--text); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Trending Now
                        </h4>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($trending_posts->take(5) as $ti => $tp)
                            <div style="display: flex; gap: 0.85rem; align-items: flex-start; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <span class="latest-number latest-number--red" style="font-size: 0.75rem; width: 22px; height: 22px; line-height: 22px; flex-shrink: 0;">{{ $ti + 1 }}</span>
                                <div style="flex: 1; min-width: 0;">
                                    <h5 style="font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 700; line-height: 1.35; margin: 0 0 0.35rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <a href="{{ url('/post/' . $tp->slug) }}" style="color: var(--text); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">{{ $tp->title }}</a>
                                    </h5>
                                    <span style="font-size: 0.72rem; color: #94a3b8;">
                                        {{ $tp->published_at ? $tp->published_at->format('M j, Y') : $tp->created_at->format('M j, Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 3. HALF PAGE AD SLOT (300×600) --}}
            @php
                $adCatSidebarHalf = \App\Models\Ad::getSlot('category_sidebar_halfpage', 'category')
                    ?? \App\Models\Ad::getSlot('home_sidebar_halfpage', 'home');
            @endphp
            <div class="sidebar-widget" style="margin-bottom: 2rem;">
                <div class="ad-banner-box" style="padding: 1.25rem 1rem;">
                    <span class="ad-banner-label">ADVERTISEMENT</span>
                    <div class="ad-content" style="display: flex; align-items: center; justify-content: center;">
                        @if($adCatSidebarHalf)
                            @if($adCatSidebarHalf->link)
                                <a href="{{ $adCatSidebarHalf->link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($adCatSidebarHalf->image_path) }}" alt="{{ $adCatSidebarHalf->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                </a>
                            @else
                                <img src="{{ asset($adCatSidebarHalf->image_path) }}" alt="{{ $adCatSidebarHalf->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                            @endif
                        @else
                            <div class="ad-placeholder" style="min-height: 600px; width: 100%;">
                                <span>Half Page Ad Slot — 300×600</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 4. SIDEBAR NEWSLETTER WIDGET --}}
            <div class="sidebar-widget" style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); color: #ffffff; padding: 1.75rem; border-radius: var(--radius-lg); text-align: center;">
                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; margin: 0 0 0.5rem 0; color: #ffffff;">TechTV Daily</h4>
                <p style="color: #cbd5e1; font-size: 0.85rem; line-height: 1.5; margin-bottom: 1.25rem;">Stay informed with the top technology, innovation, and business analysis delivered straight to your inbox.</p>
                <form class="footer-newsletter-form" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <input type="email" placeholder="Your email address" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); color: #ffffff; font-size: 0.88rem;">
                    <button type="submit" style="background: var(--accent); color: #ffffff; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: background 0.2s;">Subscribe Now</button>
                </form>
            </div>

        </aside>

    </div>
</div>

{{-- ============================================================
     4. BOTTOM LEADERBOARD AD BANNER (728×90)
     ============================================================ --}}
@php
    $adCatFooter = \App\Models\Ad::getSlot('category_footer_leaderboard', 'category')
        ?? \App\Models\Ad::getSlot('global_footer_leaderboard', 'global');
@endphp
<div class="container" style="padding-bottom: 4rem;">
    <div class="ad-banner-section" style="padding: 0;">
        <div class="ad-banner-box">
            <span class="ad-banner-label">ADVERTISEMENT</span>
            <div class="ad-banner-content">
                @if($adCatFooter)
                    @if($adCatFooter->link)
                        <a href="{{ $adCatFooter->link }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($adCatFooter->image_path) }}" alt="{{ $adCatFooter->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                        </a>
                    @else
                        <img src="{{ asset($adCatFooter->image_path) }}" alt="{{ $adCatFooter->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                    @endif
                @else
                    <div class="ad-placeholder">
                        <span>Footer Leaderboard Ad Space — 728×90</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
