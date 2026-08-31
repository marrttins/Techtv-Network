<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminVideoController extends Controller
{
    /**
     * Helper to extract YouTube video ID from various URL formats
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) return null;
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null;
    }

    /**
     * Helper to get YouTube thumbnail from video URL
     */
    public static function getYoutubeThumbnail(?string $url): ?string
    {
        $ytId = self::extractYoutubeId($url);
        return $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : null;
    }

    /**
     * Display a listing of TechTV Videos.
     */
    public function index()
    {
        $videoCategory = Category::where('slug', 'videos')->first();
        $catId = $videoCategory ? $videoCategory->id : 0;

        $videos = Post::where(function($q) use ($catId) {
                if ($catId) {
                    $q->where('category_id', $catId);
                }
                $q->orWhere(function($sub) {
                    $sub->whereNotNull('video_url')->where('video_url', '!=', '');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $liveSettings = \Illuminate\Support\Facades\DB::table('settings')
            ->whereIn('key', ['youtube_live_url', 'youtube_live_title', 'youtube_live_active'])
            ->pluck('value', 'key')
            ->all();

        return view('admin.videos.index', compact('videos', 'liveSettings'));
    }

    /**
     * Update YouTube Live Stream settings.
     */
    public function updateLiveStream(Request $request)
    {
        $validated = $request->validate([
            'youtube_live_url' => 'nullable|string',
            'youtube_live_title' => 'nullable|string|max:255',
            'youtube_live_active' => 'nullable|in:0,1',
        ]);

        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'youtube_live_url'],
            ['value' => $validated['youtube_live_url'] ?? '', 'updated_at' => now()]
        );

        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'youtube_live_title'],
            ['value' => $validated['youtube_live_title'] ?: 'TechTV Live Broadcast', 'updated_at' => now()]
        );

        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'youtube_live_active'],
            ['value' => $request->has('youtube_live_active') && $request->youtube_live_active == '1' ? '1' : '0', 'updated_at' => now()]
        );

        return redirect('/admin/videos')->with('success', 'YouTube Live Stream settings updated successfully!');
    }

    /**
     * Show the form for creating a new TechTV Video.
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created TechTV Video in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image' => 'nullable|image|max:4096',
            'status' => 'required|in:publish,draft',
        ]);

        $videoCategory = Category::firstOrCreate(
            ['slug' => 'videos'],
            ['name' => 'Videos', 'description' => 'TechTV YouTube Videos & Broadcasts']
        );

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } else {
            // Auto fallback to YouTube thumbnail if no custom image was uploaded
            $ytThumb = self::getYoutubeThumbnail($validated['video_url']);
            $imagePath = $ytThumb ?: null;
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'video_url' => $validated['video_url'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'] ?: ($validated['excerpt'] ?: $validated['title']),
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'category_id' => $videoCategory->id,
            'author_id' => auth()->id(),
            'published_at' => $validated['status'] === 'publish' ? now() : null,
        ]);

        return redirect('/admin/videos')->with('success', 'TechTV Video added successfully!');
    }

    /**
     * Show the form for editing the specified TechTV Video.
     */
    public function edit($id)
    {
        $video = Post::findOrFail($id);
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update the specified TechTV Video in database.
     */
    public function update(Request $request, $id)
    {
        $video = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image' => 'nullable|image|max:4096',
            'status' => 'required|in:publish,draft',
        ]);

        $videoCategory = Category::firstOrCreate(
            ['slug' => 'videos'],
            ['name' => 'Videos', 'description' => 'TechTV YouTube Videos & Broadcasts']
        );

        if ($request->hasFile('featured_image')) {
            // Delete old custom image if stored locally
            if ($video->featured_image && !Str::startsWith($video->featured_image, 'http')) {
                Storage::disk('public')->delete($video->featured_image);
            }
            $video->featured_image = $request->file('featured_image')->store('posts', 'public');
        } elseif (empty($video->featured_image) || Str::contains($video->featured_image, 'img.youtube.com')) {
            // Auto update YouTube thumbnail if video URL changed
            $ytThumb = self::getYoutubeThumbnail($validated['video_url']);
            if ($ytThumb) {
                $video->featured_image = $ytThumb;
            }
        }

        $video->title = $validated['title'];
        $video->video_url = $validated['video_url'];
        $video->excerpt = $validated['excerpt'] ?? null;
        if (!empty($validated['body'])) {
            $video->body = $validated['body'];
        }
        $video->status = $validated['status'];
        $video->category_id = $videoCategory->id;

        if ($validated['status'] === 'publish' && !$video->published_at) {
            $video->published_at = now();
        }

        $video->save();

        return redirect('/admin/videos')->with('success', 'TechTV Video updated successfully!');
    }

    /**
     * Remove the specified TechTV Video from database.
     */
    public function destroy($id)
    {
        $video = Post::findOrFail($id);

        if ($video->featured_image && !Str::startsWith($video->featured_image, 'http')) {
            Storage::disk('public')->delete($video->featured_image);
        }

        $video->delete();

        return redirect('/admin/videos')->with('success', 'TechTV Video deleted successfully!');
    }
}
