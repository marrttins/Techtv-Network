<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;

class MigrateWordPressApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:wordpress-api {--limit= : Limit the number of posts to fetch for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes the live WordPress site using the REST API to populate the Laravel database (Optimized)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting WordPress REST API Migration (Optimized)...');
        $limit = $this->option('limit');

        // Disabling SSL check since local environments can have SSL issues
        $http = Http::withoutVerifying()
            ->timeout(60)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ]);

        $baseUrl = 'https://techtvnetwork.ng/wp-json/wp/v2';

        // 1. Fetch Users (Authors) - We fetch users since there are very few users
        $this->info('Migrating Users...');
        $userMap = []; // Maps WP user ID to local user ID
        try {
            $response = $http->get("$baseUrl/users", [
                'per_page' => 50,
            ]);

            if ($response->successful()) {
                $users = $response->json();
                foreach ($users as $u) {
                    $user = User::updateOrCreate(
                        ['email' => $u['slug'] . '@techtvnetwork.ng'],
                        [
                            'name' => $u['name'],
                            'password' => Hash::make('password'),
                        ]
                    );
                    $userMap[$u['id']] = $user->id;
                }
            }
        } catch (\Exception $e) {
            $this->error('Failed to migrate users from API: ' . $e->getMessage());
        }

        // Ensure at least one admin exists
        if (User::count() == 0) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@techtvnetwork.ng',
                'password' => Hash::make('password'),
            ]);
            $userMap[1] = $admin->id;
        }

        // 2. Fetch Posts
        $this->info('Migrating Posts...');
        $page = 1;
        $postCount = 0;
        $postMap = []; // Maps WP post ID to local post ID

        while (true) {
            $this->info("Fetching posts page $page (per_page=5)...");
            try {
                $response = $http->timeout(60)->get("$baseUrl/posts", [
                    'per_page' => 5,
                    'page' => $page,
                    '_embed' => 1,
                ]);

                if ($response->failed() || empty($response->json())) {
                    $this->info("No more posts or request failed.");
                    break;
                }

                $posts = $response->json();
                foreach ($posts as $p) {
                    // Check limit
                    if ($limit && $postCount >= $limit) {
                        break 2;
                    }

                    // Resolve Author
                    $localAuthorId = $userMap[$p['author']] ?? User::first()->id;

                    // Resolve Categories & Tags on the fly from embedded data
                    $localCategoryId = null;
                    $localTagIds = [];

                    if (isset($p['_embedded']['wp:term'])) {
                        foreach ($p['_embedded']['wp:term'] as $termGroup) {
                            foreach ($termGroup as $term) {
                                if ($term['taxonomy'] === 'category') {
                                    $cat = Category::updateOrCreate(
                                        ['slug' => $term['slug']],
                                        ['name' => $term['name']]
                                    );
                                    // Assign first category as main category
                                    if (!$localCategoryId) {
                                        $localCategoryId = $cat->id;
                                    }
                                } elseif ($term['taxonomy'] === 'post_tag') {
                                    $tag = Tag::updateOrCreate(
                                        ['slug' => $term['slug']],
                                        ['name' => $term['name']]
                                    );
                                    $localTagIds[] = $tag->id;
                                }
                            }
                        }
                    }

                    // Resolve Featured Image
                    $featuredImageUrl = null;
                    $localFeaturedImagePath = null;
                    if (isset($p['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                        $featuredImageUrl = $p['_embedded']['wp:featuredmedia'][0]['source_url'];
                    }

                    if ($featuredImageUrl) {
                        $localFeaturedImagePath = $this->downloadFeaturedImage($featuredImageUrl);
                    }

                    // Create/Update Post
                    $post = Post::updateOrCreate(
                        ['slug' => $p['slug']],
                        [
                            'title' => $p['title']['rendered'],
                            'excerpt' => strip_tags($p['excerpt']['rendered']),
                            'body' => $p['content']['rendered'],
                            'featured_image' => $localFeaturedImagePath,
                            'status' => $p['status'] === 'publish' ? 'publish' : 'draft',
                            'author_id' => $localAuthorId,
                            'category_id' => $localCategoryId,
                            'published_at' => $p['date'] ? \Carbon\Carbon::parse($p['date']) : null,
                            'created_at' => $p['date'] ? \Carbon\Carbon::parse($p['date']) : null,
                            'updated_at' => $p['modified'] ? \Carbon\Carbon::parse($p['modified']) : null,
                        ]
                    );

                    $postMap[$p['id']] = $post->id;
                    $postCount++;

                    // Sync Tags
                    if (!empty($localTagIds)) {
                        $post->tags()->sync($localTagIds);
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error on page $page: " . $e->getMessage() . " - Retrying or continuing to next page...");
            }

            $page++;
        }

        $this->info("Posts completed. Total migrated: $postCount");

        // 3. Fetch Comments
        $this->info('Migrating Comments...');
        $page = 1;
        $commentMap = []; // Maps WP comment ID to local comment ID
        $commentsToUpdateParent = [];

        while (true) {
            $this->info("Fetching comments page $page...");
            try {
                $response = $http->get("$baseUrl/comments", [
                    'per_page' => 50,
                    'page' => $page,
                ]);

                if ($response->failed() || empty($response->json())) {
                    break;
                }

                $comments = $response->json();
                foreach ($comments as $c) {
                    $localPostId = $postMap[$c['post']] ?? null;
                    if (!$localPostId) {
                        continue;
                    }

                    $comment = Comment::updateOrCreate(
                        ['id' => $c['id']],
                        [
                            'author_name' => $c['author_name'],
                            'author_email' => $c['author_email'] ?: 'anonymous@example.com',
                            'author_url' => $c['author_url'] ?? null,
                            'content' => $c['content']['rendered'],
                            'status' => $c['status'] === 'approved' ? 'approved' : 'pending',
                            'post_id' => $localPostId,
                            'created_at' => \Carbon\Carbon::parse($c['date']),
                            'updated_at' => \Carbon\Carbon::parse($c['date']),
                        ]
                    );

                    $commentMap[$c['id']] = $comment->id;

                    if ($c['parent'] > 0) {
                        $commentsToUpdateParent[$comment->id] = $c['parent'];
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error migrating comments: " . $e->getMessage());
                break;
            }
            $page++;
        }

        // Update Comment Parents
        foreach ($commentsToUpdateParent as $commentId => $wpParentId) {
            if (isset($commentMap[$wpParentId])) {
                Comment::where('id', $commentId)->update([
                    'parent_id' => $commentMap[$wpParentId]
                ]);
            }
        }

        // Update comment counts
        $this->info('Updating comment counts...');
        foreach (Post::all() as $post) {
            $post->update([
                'comments_count' => $post->comments()->where('status', 'approved')->count()
            ]);
        }

        $this->info('Migration completed successfully!');
    }

    /**
     * Downloads the featured image and returns the local public path.
     */
    private function downloadFeaturedImage($url)
    {
        $filename = basename(parse_url($url, PHP_URL_PATH));
        if (empty($filename)) {
            $filename = Str::random(10) . '.jpg';
        }

        $filename = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $filename);

        $localDir = public_path('uploads');
        if (!file_exists($localDir)) {
            mkdir($localDir, 0755, true);
        }

        $localPath = $localDir . '/' . $filename;
        $dbPath = 'uploads/' . $filename;

        if (file_exists($localPath)) {
            return $dbPath;
        }

        try {
            // Short timeout (3s) for image download; fallback to live URL if slow
            $response = Http::withoutVerifying()->timeout(3)->get($url);
            if ($response->successful()) {
                file_put_contents($localPath, $response->body());
                return $dbPath;
            }
        } catch (\Exception $e) {
            // Silently fallback to direct URL on timeout/failure
        }

        return $url;
    }
}
