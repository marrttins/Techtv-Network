<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Newsletter;

class BlogController extends Controller
{
    public function index()
    {
        // Fetch enough posts for all sections (50 latest)
        $allPosts = Post::with(['category', 'author'])
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(50)
            ->get();

        // Hero: #1 post
        $hero = $allPosts->first();

        // Hero slides: top 5 posts (auto-cycling)
        $hero_slides = $allPosts->take(5)->values();

        // Ticker: next 4 (posts 2-5)
        $ticker_posts = $allPosts->slice(1, 4)->values();

        // Spotlight / secondary slides: posts 6-8
        $spotlight_posts = $allPosts->slice(5, 3)->values();
        $secondary_slides = $spotlight_posts;

        // Feed list: posts 8-12
        $feed_posts = $allPosts->slice(7, 5)->values();

        // Bottom tech row: posts 13-17
        $tech_posts = $allPosts->slice(12, 5)->values();

        // Trending: top by view_count
        $trending_posts = Post::with(['category'])
            ->where('status', 'publish')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        // Recent posts for sidebar
        $recent_posts = $allPosts->take(5)->values();

        // Categories for grid (top categories with at least 3 published posts)
        $categories = Category::withCount(['posts' => function($q) {
                $q->where('status', 'publish');
            }])
            ->having('posts_count', '>=', 3)
            ->orderBy('posts_count', 'desc')
            ->take(6)
            ->get();

        // Paginated posts for "More Articles"
        $posts = Post::with(['category', 'author'])
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $activeAd = \App\Models\Ad::where('location', 'under_slider')
            ->where('is_active', true)
            ->first();

        $adAboveLatest = \App\Models\Ad::where('location', 'above_latest')
            ->where('is_active', true)
            ->first();

        $adUnderPopular = \App\Models\Ad::where('location', 'under_popular')
            ->where('is_active', true)
            ->first();

        $adUnderMustRead = \App\Models\Ad::where('location', 'under_must_read')
            ->where('is_active', true)
            ->first();

        return view('home', compact(
            'posts', 'recent_posts', 'hero', 'ticker_posts',
            'spotlight_posts', 'feed_posts', 'trending_posts',
            'tech_posts', 'categories', 'activeAd', 'adAboveLatest',
            'adUnderPopular', 'adUnderMustRead',
            'hero_slides', 'secondary_slides'
        ));
    }

    public function show($slug)
    {
        $post = Post::with(['category', 'tags', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

        $comments = $post->comments()
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Previous post (older)
        $prevPost = Post::where('status', 'publish')
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        // Next post (newer)
        $nextPost = Post::where('status', 'publish')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        // Related posts (from same category or latest)
        $relatedPosts = Post::where('status', 'publish')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function($q) use ($post) {
                $q->where('category_id', $post->category_id);
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $morePosts = Post::where('status', 'publish')
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->orderBy('published_at', 'desc')
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($morePosts);
        }

        // Sidebar: Top categories with post counts
        $topCategories = Category::withCount(['posts' => function($q) {
            $q->where('status', 'publish');
        }])->orderBy('posts_count', 'desc')->take(6)->get();

        // Sidebar: Most viewed posts
        $mostViewed = Post::where('status', 'publish')
            ->where('id', '!=', $post->id)
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        // Estimated read time (avg 200 words/min)
        $wordCount = str_word_count(strip_tags($post->body));
        $readTime = max(1, ceil($wordCount / 200));

        // Math captcha for spam prevention
        $n1 = rand(2, 9);
        $n2 = rand(1, 9);
        session(['comment_captcha_answer' => $n1 + $n2]);
        $captcha_question = "{$n1} + {$n2}";

        return view('posts.single', compact(
            'post', 'comments', 'prevPost', 'nextPost',
            'topCategories', 'mostViewed', 'relatedPosts', 'readTime',
            'captcha_question'
        ));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $trending_posts = Post::where('status', 'publish')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        $recent_posts = Post::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $title = $category->name;
        $subtitle = $category->description ?: "The latest news, deep analysis, and reports on " . $category->name;

        return view('posts.archive', compact('posts', 'category', 'trending_posts', 'recent_posts', 'title', 'subtitle'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['category', 'author'])
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $trending_posts = Post::where('status', 'publish')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        $recent_posts = Post::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $title = "#" . $tag->name;
        $subtitle = $tag->description ?: "Articles tagged with #" . $tag->name;

        return view('posts.archive', compact('posts', 'trending_posts', 'recent_posts', 'title', 'subtitle'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = Post::with(['category', 'author'])
            ->where('status', 'publish')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $trending_posts = Post::where('status', 'publish')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        $recent_posts = Post::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $title = "Search Results";
        $subtitle = "Showing results for: \"" . e($query) . "\"";

        return view('posts.archive', compact('posts', 'trending_posts', 'recent_posts', 'title', 'subtitle'));
    }

    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $expectedCaptcha = session('comment_captcha_answer');
        $userCaptcha = (int)$request->input('captcha_answer');

        if (!$expectedCaptcha || $userCaptcha !== (int)$expectedCaptcha) {
            return redirect(url('/post/' . $post->slug . '#comment-form-section'))
                        ->withErrors(['captcha_answer' => 'Incorrect security math answer. Please solve the simple math problem to post a comment.'])
                        ->withInput();
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'author_url' => 'nullable|url|max:255',
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect(url('/post/' . $post->slug . '#comment-form-section'))
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $validated = $validator->validated();

        $comment = new Comment($validated);
        $comment->post_id = $post->id;
        $comment->status = 'pending'; // Requires admin approval
        $comment->save();

        // Clear captcha from session
        session()->forget('comment_captcha_answer');

        return redirect(url('/post/' . $post->slug . '#comment-form-section'))->with('success', 'Thank you! Your comment has been submitted and is awaiting administrator approval.');
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        Newsletter::updateOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'status' => 'active']
        );

        return response()->json(['message' => 'Successfully subscribed!']);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function advertise()
    {
        return view('pages.advertise');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    public function cookiePolicy()
    {
        return view('pages.cookie-policy');
    }

    public function editorialPolicy()
    {
        return view('pages.editorial-policy');
    }

    /**
     * Generate RSS 2.0 / Atom feed for Google News, syndication, and LLM search bots.
     */
    public function feed()
    {
        $posts = Post::with(['category', 'author', 'tags'])
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(30)
            ->get();

        $content = view('feed', compact('posts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    /**
     * Output Google AdSense ads.txt content.
     */
    public function adsTxt()
    {
        $siteSettings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->all();
        $adsTxtContent = $siteSettings['ads_txt'] ?? "google.com, pub-4523098321045981, DIRECT, f08c47fec0942fa0\n# TechTV Network AdSense Verification";

        return response($adsTxtContent, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
