{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" 
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:media="http://search.yahoo.com/mrss/">

  <channel>
    <title>{{ $siteSettings['site_title'] ?? 'TechTV Network' }}</title>
    <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />
    <link>{{ url('/') }}</link>
    <description>{{ $siteSettings['site_description'] ?? 'Africa’s Voice for Technology & Business Insight. Delivering news, executive interviews, and tech analysis across Nigeria and Africa.' }}</description>
    <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
    <language>en-US</language>
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>
    <image>
        <url>{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}</url>
        <title>{{ $siteSettings['site_title'] ?? 'TechTV Network' }}</title>
        <link>{{ url('/') }}</link>
    </image>

    @foreach($posts as $post)
    <item>
      <title><![CDATA[{{ $post->title }}]]></title>
      <link>{{ url('/post/' . $post->slug) }}</link>
      <pubDate>{{ ($post->published_at ?? $post->created_at)->toRfc2822String() }}</pubDate>
      <dc:creator><![CDATA[{{ $post->author ? $post->author->name : 'TechTV Editorial Team' }}]]></dc:creator>
      <category><![CDATA[{{ $post->category ? $post->category->name : 'Technology' }}]]></category>
      <guid isPermaLink="true">{{ url('/post/' . $post->slug) }}</guid>
      <description><![CDATA[{{ Str::limit(strip_tags($post->excerpt ?: $post->body), 280) }}]]></description>
      <content:encoded><![CDATA[
        @if($post->featured_image_url)
          <p><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" style="max-width:100%; height:auto;" /></p>
        @endif
        {!! $post->body !!}
      ]]></content:encoded>
      @if($post->featured_image_url)
        <media:content url="{{ $post->featured_image_url }}" medium="image">
          <media:title><![CDATA[{{ $post->title }}]]></media:title>
        </media:content>
      @endif
    </item>
    @endforeach
  </channel>
</rss>
