<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap feed for search engines and LLM crawlers.
     */
    public function index(): Response
    {
        // Get all published posts, ordered by newest update date
        $posts = Post::where('status', 'publish')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $categories = Category::all();

        $content = view('sitemap', compact('posts', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
