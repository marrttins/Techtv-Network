@extends('layouts.layout')

@section('title', 'TechTV Network | Tech, Innovation & Business Analysis')

@section('schema_json')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "WebSite",
      "name": "{{ $siteSettings['site_title'] ?? 'TechTV Network' }}",
      "alternateName": "TechTV",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
@endsection

@section('content')

    {{-- ============================================================
    LATEST NEWS SCROLLING TICKER
    ============================================================ --}}
    <div class="latest-news-ticker" style="background: var(--accent, #e02020);">
        <div class="container latest-news-ticker-inner">
            <span class="ticker-label" style="background: #0B193C; color: #ffffff;">LATEST NEWS</span>
            <div class="ticker-track-wrapper">
                <div class="ticker-track" id="ticker-track">
                    @php
                        $tickerItems = \App\Models\Post::where('status', 'publish')
                            ->orderBy('published_at', 'desc')
                            ->take(10)
                            ->get();
                    @endphp
                    @foreach($tickerItems as $tItem)
                        <a href="{{ url('/post/' . $tItem->slug) }}" class="ticker-item">
                            {{ $tItem->title }}
                            <span class="ticker-sep">●</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    ADVERTISEMENT BANNER (UNDER TICKER / TOP LEADERBOARD)
    ============================================================ --}}
    @php
        $adUnderTicker = \App\Models\Ad::getSlot('home_under_ticker', 'home') 
            ?? \App\Models\Ad::getSlot('home_header_leaderboard', 'home') 
            ?? \App\Models\Ad::getSlot('global_header_leaderboard', 'global');
    @endphp
    <div class="ad-banner-section">
        <div class="container">
            <div class="ad-banner-box">
                <span class="ad-banner-label">ADVERTISEMENT</span>
                <div class="ad-banner-content">
                    @if($adUnderTicker)
                        @if($adUnderTicker->link)
                            <a href="{{ $adUnderTicker->link }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset($adUnderTicker->image_path) }}" alt="{{ $adUnderTicker->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                            </a>
                        @else
                            <img src="{{ asset($adUnderTicker->image_path) }}" alt="{{ $adUnderTicker->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                        @endif
                    @else
                        <div class="ad-placeholder">
                            <span>Ad Space — 728×90 / 970×150</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    NEWS + LATEST TWO-COLUMN SECTION
    ============================================================ --}}
    <div class="container news-latest-section">
        {{-- Left: NEWS featured post --}}
        <div class="news-left-col">
            <span class="badge-pill-accent">NEWS</span>
            @php
                $newsPost = \App\Models\Post::where('status', 'publish')
                    ->orderBy('published_at', 'desc')
                    ->first();
            @endphp
            @if($newsPost)
                <a href="{{ url('/post/' . $newsPost->slug) }}" class="news-featured-card">
                    <div class="news-featured-img">
                        <img src="{{ $newsPost->featured_image_url }}"
                            onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $newsPost->id }}/700/450';"
                            alt="{{ $newsPost->title }}" loading="lazy">
                    </div>
                    <h3 class="news-featured-title">{{ $newsPost->title }}</h3>
                    <p class="news-featured-excerpt">
                        {{ $newsPost->excerpt ?: Str::limit(strip_tags($newsPost->body ?? $newsPost->content ?? ''), 220) }}</p>
                    <div class="news-featured-meta">
                        <span>By {{ $newsPost->author ? $newsPost->author->name : 'TechTV Network' }}</span>
                        <span>•</span>
                        <span>{{ $newsPost->published_at ? $newsPost->published_at->format('F j, Y') : $newsPost->created_at->format('F j, Y') }}</span>
                    </div>
                </a>
            @endif
        </div>

        {{-- Right: LATEST numbered list --}}
        <div class="news-right-col">
            <h3 class="latest-sidebar-heading"><span class="latest-bar"></span> LATEST</h3>
            <div class="latest-numbered-list">
                @php
                    $latestSidebar = \App\Models\Post::where('status', 'publish')
                        ->orderBy('published_at', 'desc')
                        ->skip(1)->take(4)
                        ->get();
                @endphp
                @foreach($latestSidebar as $idx => $lPost)
                    <a href="{{ url('/post/' . $lPost->slug) }}" class="latest-numbered-item">
                        <span class="latest-number">{{ $idx + 1 }}</span>
                        <span class="latest-item-title">{{ $lPost->title }}</span>
                    </a>
                @endforeach
            </div>

            {{-- MOST READ --}}
            <h3 class="latest-sidebar-heading" style="margin-top:1.5rem;"><span class="latest-bar"></span> MOST READ</h3>
            <div class="latest-numbered-list">
                @php
                    $mostRead = \App\Models\Post::where('status', 'publish')
                        ->orderBy('view_count', 'desc')
                        ->take(4)
                        ->get();
                @endphp
                @foreach($mostRead as $idx => $mrPost)
                    <a href="{{ url('/post/' . $mrPost->slug) }}" class="latest-numbered-item">
                        <span class="latest-number latest-number--red">{{ $idx + 1 }}</span>
                        <span class="latest-item-title">{{ $mrPost->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

        {{-- ============================================================
        ADVERTISEMENT BANNER & YOUTUBE LIVE (2-COLUMN LAYOUT)
        ============================================================ --}}
        @php
            $adUnderSlider = \App\Models\Ad::getSlot('home_under_slider', 'home') 
                ?? \App\Models\Ad::getSlot('under_slider', 'home');
            
            $liveActive = ($siteSettings['youtube_live_active'] ?? '0') == '1';
            $liveUrl = $siteSettings['youtube_live_url'] ?? '';
            $liveTitle = $siteSettings['youtube_live_title'] ?? 'TechTV Live Broadcast';
            $liveYtId = !empty($liveUrl) ? \App\Http\Controllers\AdminVideoController::extractYoutubeId($liveUrl) : null;
            $isStreaming = $liveActive && !empty($liveYtId);
        @endphp
        <div id="watch-live" class="ad-live-split-section" style="margin-bottom: 2.5rem; scroll-margin-top: 90px;">
            <div class="ad-live-grid">
                
                {{-- Left Column: Advertisement Banner --}}
                <div class="ad-live-col ad-live-col--ad">
                    <div class="ad-banner-box" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between; margin: 0; box-sizing: border-box;">
                        <span class="ad-banner-label">SPONSORED ADVERTISEMENT</span>
                        <div class="ad-banner-content" style="flex: 1; display: flex; align-items: center; justify-content: center; min-height: 190px;">
                            @if($adUnderSlider)
                                @if($adUnderSlider->link)
                                    <a href="{{ $adUnderSlider->link }}" target="_blank" rel="noopener noreferrer" style="display: block; width: 100%; height: 100%;">
                                        <img src="{{ asset($adUnderSlider->image_path) }}" alt="{{ $adUnderSlider->name }}" style="max-width: 100%; max-height: 220px; width: 100%; object-fit: contain; display: block; margin: 0 auto; border-radius: 6px;">
                                    </a>
                                @else
                                    <img src="{{ asset($adUnderSlider->image_path) }}" alt="{{ $adUnderSlider->name }}" style="max-width: 100%; max-height: 220px; width: 100%; object-fit: contain; display: block; margin: 0 auto; border-radius: 6px;">
                                @endif
                            @else
                                <div class="ad-placeholder" style="width: 100%; height: 100%; min-height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; background: #ffffff; border: 1.5px dashed #cbd5e1; border-radius: 8px;">
                                    <span style="font-size: 1.5rem;">📢</span>
                                    <span style="font-weight: 700; color: #64748b; font-size: 0.9rem;">Advertise with TechTV Network</span>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">High-Impact Banner Space — Connect with Decision Makers</span>
                                    <a href="{{ url('/advertise') }}" style="margin-top: 0.25rem; font-size: 0.78rem; color: var(--accent, #e02020); font-weight: 700; text-decoration: underline;">Learn More &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: YouTube Live Watch Now --}}
                <div class="ad-live-col ad-live-col--live">
                    <div class="live-watch-card" style="background: #0B193C; border-radius: 12px; overflow: hidden; height: 100%; display: flex; flex-direction: column; box-shadow: 0 4px 16px rgba(11, 25, 60, 0.15); position: relative;">
                        
                        {{-- Live Card Header --}}
                        <div style="background: #071026; padding: 0.65rem 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08);">
                            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
                                @if($isStreaming)
                                    <span class="live-pulse-badge" style="background: #e02020; color: #ffffff; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.3rem; letter-spacing: 0.05em;">
                                        <span class="live-dot-animate" style="width: 6px; height: 6px; border-radius: 50%; background: #ffffff;"></span>
                                        LIVE NOW
                                    </span>
                                @else
                                    <span style="background: rgba(255,255,255,0.12); color: #94a3b8; font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 4px; letter-spacing: 0.05em;">
                                        TECHTV BROADCAST
                                    </span>
                                @endif
                                <span style="font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $isStreaming ? $liveTitle : 'TechTV YouTube Live' }}
                                </span>
                            </div>
                            <a href="https://www.youtube.com/@techtvnetwork" target="_blank" rel="noopener noreferrer" style="color: #cbd5e1; font-size: 0.72rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; flex-shrink: 0;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#cbd5e1'">
                                <span>YouTube</span>
                                <span>↗</span>
                            </a>
                        </div>

                        {{-- Player Area --}}
                        <div class="live-player-container" style="flex: 1; min-height: 200px; position: relative; background: #000000; display: flex; align-items: center; justify-content: center;">
                            @if($isStreaming)
                                {{-- Embedded YouTube Live with Autoplay --}}
                                <iframe 
                                    src="https://www.youtube-nocookie.com/embed/{{ $liveYtId }}?autoplay=1&mute=1&playsinline=1&rel=0&modestbranding=1" 
                                    title="{{ $liveTitle }}"
                                    style="width: 100%; height: 100%; min-height: 215px; border: 0; display: block;" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                                </iframe>
                            @else
                                {{-- Standby / Offline Interactive Banner --}}
                                <div id="live-standby-screen" style="width: 100%; height: 100%; min-height: 215px; position: relative; background: radial-gradient(circle at center, #1e293b 0%, #071026 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center; cursor: pointer;" onclick="openNextProgramModal()">
                                    
                                    {{-- Background Pattern --}}
                                    <div style="position: absolute; inset: 0; opacity: 0.15; background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 16px 16px; pointer-events: none;"></div>
                                    
                                    <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center;">
                                        <div class="live-play-pulse-btn" style="width: 54px; height: 54px; border-radius: 50%; background: var(--accent, #e02020); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 0.75rem; box-shadow: 0 0 20px rgba(224, 32, 32, 0.6); transition: transform 0.2s ease;">
                                            ▶
                                        </div>
                                        <h4 style="color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 700; margin: 0 0 0.25rem 0;">
                                            Watch Live Stream
                                        </h4>
                                        <p style="color: #94a3b8; font-size: 0.78rem; margin: 0 0 0.6rem 0;">
                                            Click to tune into TechTV Special Broadcasts
                                        </p>
                                        <span style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); color: #e2e8f0; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.7rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                            <span>⚡</span> Watch Now
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Next Program Modal / Alert for Offline Stream --}}
        <div id="next-program-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;" onclick="if(event.target === this) closeNextProgramModal()">
            <div style="background: #ffffff; border-radius: 16px; max-width: 460px; width: 100%; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); animation: pop-in 0.25s ease-out; margin: auto;">
                <div style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); color: #ffffff; padding: 1.5rem; text-align: center; position: relative;">
                    <button type="button" onclick="closeNextProgramModal()" style="position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.15); border: none; color: #fff; width: 28px; height: 28px; border-radius: 50%; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(224, 32, 32, 0.15); border: 2px solid #e02020; color: #e02020; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        📺
                    </div>
                    <span style="background: #e02020; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 4px; letter-spacing: 0.05em; text-transform: uppercase;">
                        BROADCAST SCHEDULE
                    </span>
                    <h3 style="margin: 0.6rem 0 0 0; font-family: 'Poppins', sans-serif; font-size: 1.3rem; font-weight: 800; color: #ffffff;">
                        NEXT Program Starting Soon
                    </h3>
                </div>
                <div style="padding: 1.5rem; text-align: center;">
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin: 0 0 1.25rem 0;">
                        There is no live broadcast on air at this moment. Our upcoming tech analysis program and live industry discussions will be streaming shortly.
                    </p>
                    <p style="color: #64748b; font-size: 0.85rem; margin: 0 0 1.5rem 0;">
                        In the meantime, explore our recorded interviews, thought leadership videos, and top news reports!
                    </p>
                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <button type="button" onclick="closeNextProgramModal()" style="padding: 0.65rem 1.25rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                            Close
                        </button>
                        <a href="https://www.youtube.com/@techtvnetwork" target="_blank" rel="noopener noreferrer" style="padding: 0.65rem 1.25rem; border-radius: 6px; border: none; background: #e02020; color: #ffffff; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                            <span>Visit YouTube Channel</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        1. TOP FEATURED GRID (3-COLUMN LAYOUT)
        ============================================================ --}}
        <div class="home-featured-grid">

            {{-- Left Column: Large Featured Post --}}
            @if($hero)
                <div class="featured-card-large">
                    <a href="{{ url('/post/' . $hero->slug) }}">
                        <img src="{{ $hero->featured_image_url }}"
                            onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $hero->id }}/900/550';"
                            alt="{{ $hero->title }}">
                        <div class="featured-card-large-overlay"></div>
                        <div class="featured-card-large-content">
                            @if($hero->category)
                                <span class="badge-pill-category">{{ $hero->category->name }}</span>
                            @endif
                            <h2 class="featured-card-large-title">{{ $hero->title }}</h2>
                            <div class="featured-card-large-meta">
                                <span>By {{ $hero->author ? $hero->author->name : 'TechTV Network' }}</span>
                                <span>•</span>
                                <span>{{ $hero->published_at ? $hero->published_at->format('F j, Y') : $hero->created_at->format('F j, Y') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- Middle Column: Medium Post Card --}}
            @php
                $mediumPost = $ticker_posts->first();
            @endphp
            @if($mediumPost)
                <div class="featured-card-medium">
                    <a href="{{ url('/post/' . $mediumPost->slug) }}" class="featured-card-medium-img">
                        <img src="{{ $mediumPost->featured_image_url }}"
                            onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $mediumPost->id }}/500/350';"
                            alt="{{ $mediumPost->title }}">
                    </a>
                    <div class="featured-card-medium-body">
                        @if($mediumPost->category)
                            <span class="badge-pill-category"
                                style="align-self: flex-start;">{{ $mediumPost->category->name }}</span>
                        @endif
                        <h3 class="featured-card-medium-title">
                            <a href="{{ url('/post/' . $mediumPost->slug) }}">{{ $mediumPost->title }}</a>
                        </h3>
                        <p class="featured-card-medium-excerpt">
                            {{ Str::limit(strip_tags($mediumPost->excerpt ?: $mediumPost->body), 110) }}</p>
                        <div class="featured-card-medium-meta">
                            <span>By {{ $mediumPost->author ? $mediumPost->author->name : 'TechTV' }}</span>
                            <span>•</span>
                            <span>{{ $mediumPost->published_at ? $mediumPost->published_at->format('M j, Y') : $mediumPost->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Right Column: Tabbed Widget (Trending & Latest) --}}
            <div class="home-sidebar-tabs">
                <div class="sidebar-tabs-nav">
                    <button class="sidebar-tab-btn active" data-tab="tab-trending">TRENDING</button>
                    <button class="sidebar-tab-btn" data-tab="tab-latest">LATEST</button>
                </div>

                {{-- Trending Tab --}}
                <div class="sidebar-tabs-content" id="tab-trending">
                    @foreach($trending_posts->take(5) as $ti => $tp)
                        <div class="sidebar-tab-post">
                            <span class="sidebar-tab-post-num">0{{ $ti + 1 }}</span>
                            <div class="sidebar-tab-post-body">
                                <h4 class="sidebar-tab-post-title">
                                    <a href="{{ url('/post/' . $tp->slug) }}">{{ $tp->title }}</a>
                                </h4>
                                <span
                                    class="sidebar-tab-post-date">{{ $tp->published_at ? $tp->published_at->format('M j, Y') : $tp->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Latest Tab --}}
                <div class="sidebar-tabs-content" id="tab-latest" style="display: none;">
                    @foreach($recent_posts->take(5) as $li => $lp)
                        <div class="sidebar-tab-post">
                            <span class="sidebar-tab-post-num">0{{ $li + 1 }}</span>
                            <div class="sidebar-tab-post-body">
                                <h4 class="sidebar-tab-post-title">
                                    <a href="{{ url('/post/' . $lp->slug) }}">{{ $lp->title }}</a>
                                </h4>
                                <span
                                    class="sidebar-tab-post-date">{{ $lp->published_at ? $lp->published_at->format('M j, Y') : $lp->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        {{-- ============================================================
        EVENTS TRACKER BANNER WIDGET
        ============================================================ --}}
        <div class="events-tracker-banner">
            <div class="events-tracker-content">
                <div class="events-tracker-text">
                    <span class="events-tracker-badge">EVENTS TRACKER</span>
                    <h3 class="events-tracker-title">TechTV Events Tracker</h3>
                    <p class="events-tracker-desc">Conferences, summits, and industry events across Tech, Business, and Economy.</p>
                </div>
                <a href="{{ url('/advertise') }}" class="events-tracker-btn">Advertise Your Event</a>
            </div>
        </div>

        {{-- ============================================================
        2. DYNAMIC CATEGORY BLOCKS (TECHNOLOGY / BUSINESS / ECONOMY)
        ============================================================ --}}
        @foreach($categories->take(4) as $cat)
            @php
                $catPosts = $cat->posts()->where('status', 'publish')->orderBy('published_at', 'desc')->take(12)->get();
            @endphp

            @if($catPosts->count() > 0)
                <section class="category-block-section">
                    <div class="category-block-header">
                        <h3 class="category-block-title">{{ $cat->name }}</h3>
                        <a href="{{ url('/category/' . $cat->slug) }}" class="category-block-more-link">See All →</a>
                    </div>

                    <div class="category-block-grid-3col">
                        @foreach($catPosts as $cPost)
                            <div class="category-card-col">
                                <a href="{{ url('/post/' . $cPost->slug) }}" class="category-card-col-img">
                                    <img src="{{ $cPost->featured_image_url }}"
                                        onerror="this.onerror=null; this.src='https://picsum.photos/seed/cat{{ $cPost->id }}/500/300';"
                                        alt="{{ $cPost->title }}" loading="lazy">
                                </a>
                                <div class="category-card-col-body">
                                    <h4 class="category-card-col-title">
                                        <a href="{{ url('/post/' . $cPost->slug) }}">{{ $cPost->title }}</a>
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Ad Banner Slot After Each Category --}}
                @php
                    $adMidContent = \App\Models\Ad::getSlot('home_mid_leaderboard', 'home') 
                        ?? \App\Models\Ad::getSlot('above_latest', 'home');
                @endphp
                <div class="ad-banner-section" style="padding: 0 0 2.5rem;">
                    <div class="ad-banner-box">
                        <span class="ad-banner-label">ADVERTISEMENT</span>
                        <div class="ad-banner-content">
                            @if($adMidContent)
                                @if($adMidContent->link)
                                    <a href="{{ $adMidContent->link }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset($adMidContent->image_path) }}" alt="{{ $adMidContent->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                                    </a>
                                @else
                                    <img src="{{ asset($adMidContent->image_path) }}" alt="{{ $adMidContent->name }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border-radius: 6px;">
                                @endif
                            @else
                                <div class="ad-placeholder">
                                    <span>Ad Space — 728×90</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>

    {{-- ============================================================
    3. TECHECONOMY TV VIDEO SECTION (NAVY BLUE WIDGET)
    ============================================================ --}}
    <section class="techeconomy-tv-section">
        <div class="container">
            <div class="techeconomy-tv-header">
                <h3 class="techeconomy-tv-title">TechTV YouTube</h3>
                <a href="https://www.youtube.com/@techtvng" target="_blank" rel="noopener noreferrer" class="category-block-more-link" style="color: #CBD5E1;">View Channel →</a>
            </div>

            @php
                $videoCategory = \App\Models\Category::where('slug', 'videos')->first();
                $catId = $videoCategory ? $videoCategory->id : 0;
                $videoPosts = \App\Models\Post::where('status', 'publish')
                    ->where(function($q) use ($catId) {
                        if ($catId) {
                            $q->where('category_id', $catId);
                        }
                        $q->orWhere(function($sub) {
                            $sub->whereNotNull('video_url')->where('video_url', '!=', '');
                        });
                    })
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();

                if ($videoPosts->isEmpty()) {
                    $videoPosts = \App\Models\Post::where('status', 'publish')->latest()->take(4)->get();
                }
            @endphp

            <div class="video-card-grid">
                @foreach($videoPosts as $vpost)
                    @php
                        $ytUrl = $vpost->video_url ?: 'https://www.youtube.com/@techtvng';
                        $thumb = $vpost->featured_image_url;
                        if (empty($vpost->featured_image) && $vpost->video_url) {
                            $ytId = \App\Http\Controllers\AdminVideoController::extractYoutubeId($vpost->video_url);
                            if ($ytId) {
                                $thumb = "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
                            }
                        }
                    @endphp
                    <div class="video-card">
                        <a href="{{ $ytUrl }}" target="_blank" rel="noopener noreferrer">
                            <div class="video-thumbnail-wrap">
                                <img src="{{ $thumb }}"
                                    onerror="this.onerror=null; this.src='https://picsum.photos/seed/vid{{ $vpost->id }}/400/260';"
                                    alt="{{ $vpost->title }}" loading="lazy">
                                <div class="video-play-btn">
                                    <span class="video-play-icon">▶</span>
                                </div>
                            </div>
                        </a>
                        <div class="video-card-body">
                            <h4 class="video-card-title">
                                <a href="{{ $ytUrl }}" target="_blank" rel="noopener noreferrer" style="color: #ffffff;">{{ $vpost->title }}</a>
                            </h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="container">

        {{-- ============================================================
        4. OP-ED COLUMNISTS SECTION + SIDE AD BANNER (50/50 SPLIT)
        ============================================================ --}}
        @php
            $opinionPosts = $feed_posts->take(3);
        @endphp
        @if($opinionPosts->count() > 0)
            <section class="oped-split-section">
                {{-- Left Column: 50% Posts --}}
                <div class="oped-split-left">
                    <div class="category-block-header">
                        <h3 class="category-block-title">Opinion & Analysis</h3>
                    </div>
                    <div class="oped-list-stack">
                        @foreach($opinionPosts as $oi => $opost)
                            <div class="oped-card">
                                <a href="{{ url('/post/' . $opost->slug) }}" class="oped-avatar-link">
                                    <img src="{{ $opost->featured_image_url }}"
                                        onerror="this.onerror=null; this.src='https://picsum.photos/seed/op{{ $opost->id }}/150/150';"
                                        alt="{{ $opost->title }}" class="oped-avatar" loading="lazy">
                                </a>
                                <div class="oped-body">
                                    <h4 class="oped-author-name">By {{ $opost->author ? $opost->author->name : 'TechTV Editorial' }}</h4>
                                    <h5 class="oped-card-title">
                                        <a href="{{ url('/post/' . $opost->slug) }}">{{ $opost->title }}</a>
                                    </h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right Column: 50% Ad Banner --}}
                @php
                    $adOped = \App\Models\Ad::getSlot('home_sidebar_rect', 'home') 
                        ?? \App\Models\Ad::getSlot('under_popular', 'home');
                @endphp
                <div class="oped-split-right">
                    <div class="category-block-header">
                        <h3 class="category-block-title" style="color: #64748b; font-size: 1rem;">Sponsored</h3>
                    </div>
                    <div class="oped-ad-box">
                        <span class="ad-banner-label">ADVERTISEMENT</span>
                        <div class="oped-ad-content" style="display: flex; align-items: center; justify-content: center;">
                            @if($adOped)
                                @if($adOped->link)
                                    <a href="{{ $adOped->link }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset($adOped->image_path) }}" alt="{{ $adOped->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                    </a>
                                @else
                                    <img src="{{ asset($adOped->image_path) }}" alt="{{ $adOped->name }}" style="max-width: 100%; height: auto; display: block; border-radius: 6px;">
                                @endif
                            @else
                                <div class="oped-ad-placeholder">
                                    <span>Ad Space — 336×280 / Banner</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ============================================================
        5. NAVY NEWSLETTER BANNER WIDGET
        ============================================================ --}}
        <div class="newsletter-navy-banner">
            <div class="newsletter-navy-banner-content">
                <h3 class="newsletter-navy-title">Subscribe to TechTV Daily</h3>
                <p class="newsletter-navy-desc">Get the latest technology, startup news, and digital economy updates
                    directly in your inbox.</p>

                <form class="newsletter-navy-form footer-newsletter-form">
                    <input type="email" placeholder="Your Email Address" required class="newsletter-navy-input">
                    <button type="submit" class="newsletter-navy-btn">Subscribe</button>
                </form>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Tabs Logic
            const tabButtons = document.querySelectorAll('.sidebar-tab-btn');
            const tabContents = document.querySelectorAll('.sidebar-tabs-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    // Hide all tab contents
                    tabContents.forEach(content => content.style.display = 'none');

                    // Add active class to clicked button
                    this.classList.add('active');
                    // Show corresponding tab content
                    document.getElementById(targetTab).style.display = 'block';
                });
            });

            // Newsletter Submission Logic
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
                        if (res.ok) {
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

    <script>
        // Duplicate ticker items for seamless infinite scroll
        document.addEventListener('DOMContentLoaded', function () {
            const track = document.getElementById('ticker-track');
            if (track) {
                track.innerHTML += track.innerHTML;
            }
        });

        // Next Program Modal handlers
        function openNextProgramModal() {
            const modal = document.getElementById('next-program-modal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
        function closeNextProgramModal() {
            const modal = document.getElementById('next-program-modal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection