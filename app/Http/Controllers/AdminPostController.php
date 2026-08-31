<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $postIds = $request->input('post_ids', []);

        if (empty($postIds)) {
            return redirect()->back()->with('error', 'Please select at least one post to perform a bulk action.');
        }

        if ($action === 'delete') {
            $posts = Post::whereIn('id', $postIds)->get();
            foreach ($posts as $post) {
                if ($post->featured_image && File::exists(public_path($post->featured_image))) {
                    File::delete(public_path($post->featured_image));
                }
                $post->delete();
            }
            return redirect()->back()->with('success', count($postIds) . ' posts deleted successfully.');
        } elseif ($action === 'publish') {
            Post::whereIn('id', $postIds)->update([
                'status' => 'publish',
                'published_at' => now(),
            ]);
            return redirect()->back()->with('success', count($postIds) . ' posts marked as Published.');
        } elseif ($action === 'draft') {
            Post::whereIn('id', $postIds)->update([
                'status' => 'draft',
            ]);
            return redirect()->back()->with('success', count($postIds) . ' posts marked as Draft.');
        }

        return redirect()->back()->with('error', 'Invalid action selected.');
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $authors = \App\Models\User::all();
        return view('admin.posts.create', compact('categories', 'tags', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'status' => 'required|in:publish,draft',
            'category_id' => 'nullable|exists:categories,id',
            'video_url' => 'nullable|url|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image_path' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags_input' => 'nullable|string|max:1000',
            'author_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
        ]);

        // Generate Unique Slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;
        
        if (Auth::check() && Auth::user()->role === 'super-admin' && !empty($validated['author_id'])) {
            // keep author_id
        } else {
            $validated['author_id'] = Auth::id() ?: 1;
        }

        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }

        if (empty($validated['published_at']) && $validated['status'] === 'publish') {
            $validated['published_at'] = now();
        }

        // Image Upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('/uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $name);
            $validated['featured_image'] = 'uploads/' . $name;
        } elseif ($request->filled('featured_image_path')) {
            $validated['featured_image'] = $request->featured_image_path;
        }

        $post = Post::create($validated);

        $this->syncPostTags($post, $request);

        return redirect('/admin/posts')->with('success', 'Post created successfully!');
    }

    public function edit($id)
    {
        $post = Post::with('tags')->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $authors = \App\Models\User::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'authors'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'status' => 'required|in:publish,draft',
            'category_id' => 'nullable|exists:categories,id',
            'video_url' => 'nullable|url|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image_path' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags_input' => 'nullable|string|max:1000',
            'author_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
        ]);

        // Regenerate Slug if title changes
        if ($post->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $count = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
        }

        if (Auth::user()->role !== 'super-admin') {
            unset($validated['author_id']); // Ensure non-super-admins can't change author
        }

        if (empty($validated['published_at'])) {
            if ($validated['status'] === 'publish' && !$post->published_at) {
                $validated['published_at'] = now();
            } else {
                unset($validated['published_at']); // Don't overwrite with null if not provided and already published
            }
        }

        // Image Upload & Cleanup
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image && File::exists(public_path($post->featured_image))) {
                File::delete(public_path($post->featured_image));
            }

            $image = $request->file('featured_image');
            $name = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('/uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $name);
            $validated['featured_image'] = 'uploads/' . $name;
        } elseif ($request->filled('featured_image_path')) {
            $validated['featured_image'] = $request->featured_image_path;
        }

        $post->update($validated);

        $this->syncPostTags($post, $request);

        return redirect('/admin/posts')->with('success', 'Post updated successfully!');
    }

    /**
     * Process and sync up to 5 tags / SEO keywords for a post.
     */
    protected function syncPostTags(Post $post, Request $request): void
    {
        $tagIds = [];

        // 1. If custom tags_input (comma-separated string or array of keywords) is provided
        if ($request->filled('tags_input')) {
            $rawTags = is_array($request->tags_input)
                ? $request->tags_input
                : explode(',', (string)$request->tags_input);

            $tagNames = [];
            foreach ($rawTags as $tagStr) {
                $trimmed = trim(strip_tags((string)$tagStr));
                $trimmed = ltrim($trimmed, '#');
                if (!empty($trimmed) && !in_array(strtolower($trimmed), array_map('strtolower', $tagNames))) {
                    $tagNames[] = $trimmed;
                }
            }

            // Cap at 5 tags maximum
            $tagNames = array_slice($tagNames, 0, 5);

            foreach ($tagNames as $name) {
                $slug = Str::slug($name);
                if (empty($slug)) {
                    $slug = 'tag-' . Str::random(5);
                }

                $tag = Tag::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'description' => $name]
                );
                $tagIds[] = $tag->id;
            }
        } elseif ($request->has('tags') && is_array($request->tags)) {
            // Fallback checkbox array, capped at 5
            $tagIds = array_slice($request->tags, 0, 5);
        }

        if (!empty($tagIds)) {
            $post->tags()->sync(array_unique($tagIds));
        } else {
            $post->tags()->detach();
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Delete associated image
        if ($post->featured_image && File::exists(public_path($post->featured_image))) {
            File::delete(public_path($post->featured_image));
        }

        $post->delete();

        return redirect('/admin/posts')->with('success', 'Post deleted successfully!');
    }
}
