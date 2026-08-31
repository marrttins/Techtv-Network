@extends('layouts.layout')

@section('title', $post->title . ' | TechTV Network')
@section('meta_description', Str::limit(strip_tags($post->excerpt ?: $post->body), 155))
@section('meta_keywords', $post->tags->pluck('name')->implode(', '))
@section('og_type', 'article')
@section('og_image', $post->featured_image_url)

@section('schema_json')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ request()->url() }}"
  },
  "headline": "{{ $post->title }}",
  "description": "{{ Str::limit(strip_tags($post->excerpt ?: $post->body), 160) }}",
  "keywords": "{{ $post->tags->pluck('name')->implode(', ') }}",
  "image": "{{ $post->featured_image_url }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author ? $post->author->name : 'TechTV Network' }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ $siteSettings['site_title'] ?? 'TechTV Network' }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}"
    }
  },
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at ? $post->updated_at->toIso8601String() : $post->created_at->toIso8601String() }}"
}
</script>
@endsection

@section('content')

{{-- ============================================================
     1. TOP LEADERBOARD AD BANNER (728×90 / 970×150)
     ============================================================ --}}
@php
    $adPostTop = \App\Models\Ad::getSlot('post_header_leaderboard', 'post') 
        ?? \App\Models\Ad::getSlot('global_header_leaderboard', 'global');
@endphp
<div class="container" style="padding-top: 1.75rem;">
    <div class="ad-banner-section" style="padding: 0 0 1.75rem;">
        <div class="ad-banner-box">
            <span class="ad-banner-label">ADVERTISEMENT</span>
            <div class="ad-banner-content">
                @if($adPostTop)
                    @if($adPostTop->link)
                        <a href="{{ $adPostTop->link }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($adPostTop->image_path) }}" alt="{{ $adPostTop->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                        </a>
                    @else
                        <img src="{{ asset($adPostTop->image_path) }}" alt="{{ $adPostTop->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
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

<div class="sp-wrap container" style="padding-bottom: 4rem;">

    {{-- Breadcrumb Navigation --}}
    <nav class="sp-breadcrumb" aria-label="Breadcrumb" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b;">
        <a href="{{ url('/') }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='inherit'">Home</a>
        @if($post->category)
            <span class="sp-breadcrumb-sep">›</span>
            <a href="{{ url('/category/' . $post->category->slug) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='inherit'">{{ $post->category->name }}</a>
        @endif
        <span class="sp-breadcrumb-sep">›</span>
        <span class="sp-breadcrumb-current" style="color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 450px;">{{ $post->title }}</span>
    </nav>

    {{-- Layout Container: 70% Content, 30% Sidebar --}}
    <div class="sp-layout" style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 2.5rem; align-items: flex-start;">

        {{-- ===================== MAIN CONTENT AREA ===================== --}}
        <main class="sp-main" style="min-width: 0;">
            
            {{-- Category Pill --}}
            @if($post->category)
                <div style="margin-bottom: 1rem;">
                    <a href="{{ url('/category/' . $post->category->slug) }}" class="badge-pill-category" style="text-decoration: none;">
                        {{ $post->category->name }}
                    </a>
                </div>
            @endif

            {{-- Title --}}
            <h1 class="sp-title" style="font-family: 'Poppins', sans-serif; font-size: 2.25rem; font-weight: 800; color: #1e293b; line-height: 1.25; margin-bottom: 1.25rem;">
                {{ $post->title }}
            </h1>

            {{-- Excerpt / Subtitle --}}
            @if($post->excerpt)
                <p class="sp-excerpt" style="font-size: 1.08rem; line-height: 1.65; color: #475569; margin-bottom: 1.5rem; border-left: 4px solid var(--accent); padding-left: 1.25rem; font-style: italic; background: #f8fafc; padding: 1rem 1.25rem; border-radius: 0 8px 8px 0;">
                    {{ strip_tags($post->excerpt) }}
                </p>
            @endif

            {{-- Author Meta Row --}}
            <div class="sp-meta-row" style="border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 0.85rem 0; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--accent); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem;">
                        {{ strtoupper(substr($post->author ? $post->author->name : 'T', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.92rem; color: #1e293b;">
                            {{ $post->author ? $post->author->name : 'TechTV Network' }}
                        </div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">
                            Published on {{ $post->published_at ? $post->published_at->format('F j, Y') : $post->created_at->format('F j, Y') }}
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: #64748b; font-size: 0.82rem;">
                    <span>⏱ {{ $readTime }} min read</span>
                    <span>•</span>
                    <span>👁 {{ number_format($post->view_count) }} views</span>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($post->featured_image)
                <div class="sp-featured-img-wrap" style="margin-bottom: 2.25rem; border-radius: var(--radius-lg, 12px); overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); background: #f1f5f9;">
                    <img src="{{ $post->featured_image_url }}"
                         onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $post->id }}/900/500';"
                         alt="{{ $post->title }}" class="sp-featured-img" style="width: 100%; max-height: 520px; object-fit: cover; display: block;">
                </div>
            @endif

            {{-- Post Body Content --}}
            <div class="sp-body-content" style="font-size: 1.08rem; line-height: 1.85; color: #334155; margin-bottom: 2.5rem;">
                {!! $post->body !!}
            </div>

            {{-- IN-ARTICLE AD BANNER (728×90 / 336×280) --}}
            <div class="ad-banner-section" style="padding: 1.5rem 0 2.5rem;">
                <div class="ad-banner-box">
                    <span class="ad-banner-label">ADVERTISEMENT</span>
                    <div class="ad-banner-content">
                        <div class="ad-placeholder" style="min-height: 90px;">
                            <span>In-Article Sponsor Placement — 728×90 / 336×280</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            @if($post->tags->count() > 0)
                <div class="sp-tags" style="margin-bottom: 2rem; display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                    <span style="font-weight: 700; font-size: 0.8rem; color: #64748b; text-transform: uppercase;">Tags:</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ url('/tag/' . $tag->slug) }}" class="sp-tag" style="background: #F1F5F9; border: 1px solid var(--border); border-radius: 9999px; padding: 0.35rem 0.85rem; font-size: 0.82rem; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--accent)'; this.style.color='var(--accent)';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='#475569';">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Social Share Box & PROPER COPY LINK BUTTON --}}
            <div class="sp-share-box" style="background: #F8FAFC; border: 1px solid var(--border); border-radius: var(--radius-lg, 12px); padding: 1.75rem; margin-bottom: 2.5rem; text-align: center;">
                <h4 class="sp-share-title" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 700; letter-spacing: 0.05em; color: #1e293b; margin-bottom: 1.25rem; text-transform: uppercase;">
                    Share this Article
                </h4>
                <div class="sp-share-icons" style="display: flex; justify-content: center; gap: 0.85rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="sp-share-btn" title="Share on Facebook" style="width: 42px; height: 42px; border-radius: 50%; background: #1877f2; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="sp-share-btn" title="Share on X" style="width: 42px; height: 42px; border-radius: 50%; background: #000000; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="sp-share-btn" title="Share on LinkedIn" style="width: 42px; height: 42px; border-radius: 50%; background: #0a66c2; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="sp-share-btn" title="Share on WhatsApp" style="width: 42px; height: 42px; border-radius: 50%; background: #25d366; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                </div>
                
                {{-- Clean & Prominent Copy Link Box --}}
                <div style="display: flex; gap: 0.5rem; max-width: 500px; margin: 0 auto; background: #ffffff; border: 1px solid var(--border); border-radius: 9999px; padding: 0.35rem 0.35rem 0.35rem 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); align-items: center;">
                    <span style="font-size: 1rem; color: #94a3b8;">🔗</span>
                    <input type="text" id="sp-url-input" value="{{ request()->url() }}" readonly style="flex: 1; border: none; background: transparent; font-size: 0.85rem; color: #475569; outline: none; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                    <button id="copy-btn-main" onclick="copyPostUrl()" style="background: var(--accent); color: #ffffff; border: none; padding: 0.6rem 1.4rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.25s ease; white-space: nowrap;">
                        Copy Link
                    </button>
                </div>
            </div>

            {{-- Prev / Next Post Navigation Cards --}}
            <div class="sp-nav-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 3rem;">
                @if($prevPost)
                    <a href="{{ url('/post/' . $prevPost->slug) }}" class="sp-nav-card sp-nav-prev" style="display: flex; align-items: center; gap: 0.85rem; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius-md, 8px); text-decoration: none; background: #ffffff; transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
                        <img src="{{ $prevPost->featured_image_url }}"
                             onerror="this.onerror=null; this.src='https://picsum.photos/seed/prev{{ $prevPost->id }}/80/80';"
                             alt="{{ $prevPost->title }}" class="sp-nav-img" style="width: 55px; height: 55px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                        <div class="sp-nav-body" style="min-width: 0;">
                            <span class="sp-nav-label" style="font-size: 0.72rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">‹ Previous Article</span>
                            <span class="sp-nav-post-title" style="font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 700; color: #1e293b; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-top: 0.2rem;">{{ $prevPost->title }}</span>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPost)
                    <a href="{{ url('/post/' . $nextPost->slug) }}" class="sp-nav-card sp-nav-next" style="display: flex; align-items: center; flex-direction: row-reverse; gap: 0.85rem; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius-md, 8px); text-decoration: none; background: #ffffff; transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
                        <img src="{{ $nextPost->featured_image_url }}"
                             onerror="this.onerror=null; this.src='https://picsum.photos/seed/next{{ $nextPost->id }}/80/80';"
                             alt="{{ $nextPost->title }}" class="sp-nav-img" style="width: 55px; height: 55px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                        <div class="sp-nav-body" style="text-align: right; min-width: 0;">
                            <span class="sp-nav-label" style="font-size: 0.72rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">Next Article ›</span>
                            <span class="sp-nav-post-title" style="font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 700; color: #1e293b; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-top: 0.2rem;">{{ $nextPost->title }}</span>
                        </div>
                    </a>
                @endif
            </div>

            {{-- RELATED STORIES (3-CARD GRID MATCHING HOMEPAGE) --}}
            @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div class="sp-related-section" style="margin-bottom: 3.5rem;">
                    <div class="category-block-header" style="margin-bottom: 1.5rem;">
                        <h3 class="category-block-title">Related Stories</h3>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
                        @foreach($relatedPosts as $rpost)
                            <div class="category-card-col">
                                <a href="{{ url('/post/' . $rpost->slug) }}" class="category-card-col-img" style="height: 150px;">
                                    <img src="{{ $rpost->featured_image_url }}"
                                        onerror="this.onerror=null; this.src='https://picsum.photos/seed/rel{{ $rpost->id }}/400/250';"
                                        alt="{{ $rpost->title }}" loading="lazy">
                                </a>
                                <div class="category-card-col-body" style="padding: 1rem;">
                                    <h4 class="category-card-col-title" style="font-size: 0.9rem;">
                                        <a href="{{ url('/post/' . $rpost->slug) }}">{{ $rpost->title }}</a>
                                    </h4>
                                    <div class="category-card-col-meta" style="font-size: 0.72rem; color: #94a3b8; margin-top: auto; padding-top: 0.4rem;">
                                        <span>{{ $rpost->published_at ? $rpost->published_at->format('M j, Y') : $rpost->created_at->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ============================================================
                 COMMENTS & REDESIGNED CLEAN REPLY FORM WITH MATH CAPTCHA
                 ============================================================ --}}
            <div id="comments-list" class="sp-comments-section" style="border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <div class="category-block-header" style="margin-bottom: 1.5rem;">
                    <h3 class="category-block-title">Comments ({{ $comments->count() }})</h3>
                </div>

                @if(session('success'))
                    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.92rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span>✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($comments->count() === 0)
                    <p class="sp-no-comments" style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem;">No comments yet. Be the first to join the conversation!</p>
                @else
                    <div class="sp-comment-list" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2.5rem;">
                        @foreach($comments as $comment)
                            <div class="sp-comment-item" style="border: 1px solid var(--border); border-radius: var(--radius-md, 8px); padding: 1.25rem; background: #ffffff;">
                                <div class="sp-comment-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <div class="sp-comment-avatar" style="width: 34px; height: 34px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                        {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="sp-comment-author" style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">{{ $comment->author_name }}</span>
                                        <span class="sp-comment-date" style="font-size: 0.72rem; color: #94a3b8; margin-left: 0.5rem;">{{ $comment->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="sp-comment-body" style="font-size: 0.92rem; line-height: 1.6; color: #475569;">
                                    {!! nl2br(e(strip_tags($comment->content))) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- CLEAN LEAVE A REPLY FORM WITH BOT SECURITY (MATH PROBLEM) --}}
                <div id="comment-form-section" style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                    <div style="border-left: 3px solid var(--accent); padding-left: 0.75rem; margin-bottom: 1.25rem;">
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 800; color: #1e293b; margin: 0;">
                            Leave a Reply
                        </h4>
                        <p style="font-size: 0.85rem; color: #64748b; margin: 0.25rem 0 0 0;">Your email address will not be published. Required fields are marked *</p>
                    </div>

                    @if($errors->any())
                        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem;">
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/post/' . $post->slug . '/comment') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.15rem;">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Full Name *</label>
                                <input type="text" name="author_name" value="{{ old('author_name') }}" placeholder="John Doe" class="input-field" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Email Address *</label>
                                <input type="email" name="author_email" value="{{ old('author_email') }}" placeholder="john@example.com" class="input-field" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Website (Optional)</label>
                            <input type="url" name="author_url" value="{{ old('author_url') }}" placeholder="https://yourwebsite.com" class="input-field" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Comment *</label>
                            <textarea name="content" rows="5" placeholder="Share your thoughts on this article..." class="input-field" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; resize: vertical;">{{ old('content') }}</textarea>
                        </div>

                        {{-- BOT SECURITY FEATURE: MATH PROBLEM --}}
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem 1.25rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                                <span>🛡️ Anti-Bot Security Check:</span>
                                <span style="background: #fee2e2; color: #b91c1c; padding: 0.2rem 0.6rem; border-radius: 4px; font-family: monospace; font-size: 1rem;">
                                    What is {{ $captcha_question ?? '4 + 7' }} = ?
                                </span>
                            </label>
                            <input type="number" name="captcha_answer" placeholder="Enter number answer" required style="width: 180px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.55rem 0.85rem; font-size: 0.9rem; outline: none;">
                            <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 0.35rem;">Solve the simple math equation to verify you are human.</small>
                        </div>

                        <div>
                            <button type="submit" class="btn-action" style="background: var(--accent); color: #ffffff; border: none; padding: 0.85rem 2rem; border-radius: 9999px; font-weight: 700; font-size: 0.92rem; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 14px rgba(224,32,32,0.25);">
                                Post Comment →
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>

        {{-- ===================== SIDEBAR (340px) ===================== --}}
        <aside class="sp-sidebar" style="min-width: 0;">

            {{-- 1. MEDIUM RECTANGLE AD SLOT (300×250) --}}
            @php
                $adPostSidebarRect = \App\Models\Ad::getSlot('post_sidebar_rect', 'post')
                    ?? \App\Models\Ad::getSlot('home_sidebar_rect', 'home');
            @endphp
            <div class="sidebar-widget" style="margin-bottom: 2rem;">
                <div class="ad-banner-box" style="padding: 1.25rem 1rem;">
                    <span class="ad-banner-label">SPONSORED</span>
                    <div class="ad-content" style="display: flex; align-items: center; justify-content: center;">
                        @if($adPostSidebarRect)
                            @if($adPostSidebarRect->link)
                                <a href="{{ $adPostSidebarRect->link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($adPostSidebarRect->image_path) }}" alt="{{ $adPostSidebarRect->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                </a>
                            @else
                                <img src="{{ asset($adPostSidebarRect->image_path) }}" alt="{{ $adPostSidebarRect->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                            @endif
                        @else
                            <div class="ad-placeholder" style="min-height: 250px; width: 100%;">
                                <span>Medium Rectangle — 300×250</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. TECHTV YOUTUBE CHANNEL WIDGET --}}
            <div class="sidebar-widget" style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); color: #ffffff; border-radius: var(--radius-lg, 12px); padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 6px 20px rgba(11,25,60,0.15); border: 1px solid rgba(255,255,255,0.08);">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #FF0000; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(255,0,0,0.4);">
                        ▶
                    </div>
                    <div>
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #ffffff; margin: 0;">TechTV YouTube</h4>
                        <span style="font-size: 0.75rem; color: #cbd5e1;">@techtvng</span>
                    </div>
                </div>
                <p style="font-size: 0.84rem; color: #cbd5e1; line-height: 1.5; margin: 0 0 1.15rem 0;">
                    Watch our latest tech interviews, CEO spotlights, innovation documentaries, and video analysis.
                </p>
                <a href="https://www.youtube.com/@techtvng" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: #FF0000; color: #ffffff; text-decoration: none; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.88rem; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.background='#cc0000'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#FF0000'; this.style.transform='none';">
                    <span>Subscribe to Channel</span>
                    <span>→</span>
                </a>
            </div>

            {{-- 3. TRENDING NEWS (HOMEPAGE NUMBERED BADGE STYLE) --}}
            @if(isset($mostViewed) && $mostViewed->count() > 0)
                <div class="sidebar-widget" style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg, 12px); padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="border-left: 3px solid var(--accent); padding-left: 0.75rem; margin-bottom: 1.25rem;">
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Trending Now
                        </h4>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($mostViewed->take(5) as $ti => $mv)
                            <div style="display: flex; gap: 0.85rem; align-items: flex-start; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <span class="latest-number latest-number--red" style="font-size: 0.75rem; width: 22px; height: 22px; line-height: 22px; flex-shrink: 0;">{{ $ti + 1 }}</span>
                                <div style="flex: 1; min-width: 0;">
                                    <h5 style="font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 700; line-height: 1.35; margin: 0 0 0.35rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <a href="{{ url('/post/' . $mv->slug) }}" style="color: #1e293b; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#1e293b'">{{ $mv->title }}</a>
                                    </h5>
                                    <span style="font-size: 0.72rem; color: #94a3b8;">
                                        {{ $mv->published_at ? $mv->published_at->format('M j, Y') : $mv->created_at->format('M j, Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4. HALF PAGE AD SLOT (300×600) --}}
            @php
                $adPostSidebarHalf = \App\Models\Ad::getSlot('post_sidebar_halfpage', 'post')
                    ?? \App\Models\Ad::getSlot('home_sidebar_halfpage', 'home');
            @endphp
            <div class="sidebar-widget" style="margin-bottom: 2rem;">
                <div class="ad-banner-box" style="padding: 1.25rem 1rem;">
                    <span class="ad-banner-label">ADVERTISEMENT</span>
                    <div class="ad-content" style="display: flex; align-items: center; justify-content: center;">
                        @if($adPostSidebarHalf)
                            @if($adPostSidebarHalf->link)
                                <a href="{{ $adPostSidebarHalf->link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($adPostSidebarHalf->image_path) }}" alt="{{ $adPostSidebarHalf->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                </a>
                            @else
                                <img src="{{ asset($adPostSidebarHalf->image_path) }}" alt="{{ $adPostSidebarHalf->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                            @endif
                        @else
                            <div class="ad-placeholder" style="min-height: 600px; width: 100%;">
                                <span>Half Page Ad Slot — 300×600</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 5. CATEGORIES LIST --}}
            @if(isset($topCategories) && $topCategories->count() > 0)
                <div class="sidebar-widget" style="border: 1px solid var(--border); border-radius: var(--radius-lg, 12px); padding: 1.5rem; background: white; margin-bottom: 2rem;">
                    <div style="border-left: 3px solid var(--accent); padding-left: 0.75rem; margin-bottom: 1.25rem;">
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                            Top Categories
                        </h4>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($topCategories as $cat)
                            <a href="{{ url('/category/' . $cat->slug) }}" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 0.6rem 0.85rem; background: #F8FAFC; border-radius: 6px; font-size: 0.88rem; color: #334155; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='var(--accent)';" onmouseout="this.style.background='#F8FAFC'; this.style.color='#334155';">
                                <span>{{ $cat->name }}</span>
                                <span style="font-size: 0.72rem; color: #64748b; background: #E2E8F0; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">{{ $cat->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 6. NEWSLETTER WIDGET --}}
            <div class="sidebar-widget" style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); color: #ffffff; padding: 1.75rem; border-radius: var(--radius-lg, 12px); text-align: center;">
                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 800; margin: 0 0 0.5rem 0; color: #ffffff;">TechTV Daily</h4>
                <p style="color: #cbd5e1; font-size: 0.85rem; line-height: 1.5; margin-bottom: 1.25rem;">Get deep tech intelligence and digital economy stories delivered straight to your inbox.</p>
                <form class="footer-newsletter-form" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <input type="email" placeholder="Your email address" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); color: #ffffff; font-size: 0.88rem;">
                    <button type="submit" style="background: var(--accent); color: #ffffff; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: background 0.2s;">Subscribe</button>
                </form>
            </div>

        </aside>

    </div>
</div>

{{-- ============================================================
     6. BOTTOM LEADERBOARD AD BANNER (728×90)
     ============================================================ --}}
@php
    $adPostFooter = \App\Models\Ad::getSlot('post_footer_leaderboard', 'post')
        ?? \App\Models\Ad::getSlot('global_footer_leaderboard', 'global');
@endphp
<div class="container" style="padding-bottom: 4rem;">
    <div class="ad-banner-section" style="padding: 0;">
        <div class="ad-banner-box">
            <span class="ad-banner-label">ADVERTISEMENT</span>
            <div class="ad-banner-content">
                @if($adPostFooter)
                    @if($adPostFooter->link)
                        <a href="{{ $adPostFooter->link }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($adPostFooter->image_path) }}" alt="{{ $adPostFooter->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                        </a>
                    @else
                        <img src="{{ asset($adPostFooter->image_path) }}" alt="{{ $adPostFooter->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
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

@section('scripts')
<script>
function copyPostUrl() {
    const input = document.getElementById('sp-url-input');
    const btn = document.getElementById('copy-btn-main');
    
    // Copy using clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value).then(showCopied).catch(fallbackCopy);
    } else {
        fallbackCopy();
    }
    
    function fallbackCopy() {
        input.select();
        input.setSelectionRange(0, 99999);
        try {
            document.execCommand('copy');
            showCopied();
        } catch (err) {
            btn.textContent = 'Press Ctrl+C';
        }
    }
    
    function showCopied() {
        const origText = btn.textContent;
        btn.textContent = '✓ Copied!';
        btn.style.background = '#16a34a';
        setTimeout(function() {
            btn.textContent = origText;
            btn.style.background = 'var(--accent)';
        }, 2500);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Newsletter Widget Logic
    const newsletterForms = document.querySelectorAll('.footer-newsletter-form');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value;
            const btn = this.querySelector('button');
            const origText = btn.textContent;
            
            btn.textContent = '...';
            btn.disabled = true;
            try {
                const res = await fetch('{{ url("/newsletter/subscribe") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ email })
                });
                if(res.ok) {
                    btn.textContent = '✓ Subscribed!';
                    btn.style.background = '#16a34a';
                    emailInput.value = '';
                } else {
                    btn.textContent = 'Failed';
                }
            } catch (err) { 
                btn.textContent = 'Retry'; 
                btn.disabled = false;
            }
        });
    });
});
</script>
@endsection
