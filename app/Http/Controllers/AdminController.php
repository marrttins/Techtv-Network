<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Newsletter;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user) {
            // 1. Check if user is currently locked
            if ($user->locked_until && $user->locked_until->isFuture()) {
                // If user is permanently locked (failed again after cooldown)
                if ($user->lockout_count >= 2) {
                    return back()->withErrors([
                        'email' => 'Your account is locked due to repeated failed login attempts. You must reset your password to regain access.',
                    ])->with('forced_forgot_password', true)->with('locked_email', $user->email)->withInput();
                }

                // Temporary 30-minute lockout
                $minutesLeft = now()->diffInMinutes($user->locked_until) + 1;
                return back()->withErrors([
                    'email' => "Account temporarily locked. Too many failed attempts. Please retry in {$minutesLeft} minute(s) or reset your password.",
                ])->with('show_forgot_link', true)->with('locked_email', $user->email)->withInput();
            }

            // 2. Check if user previously had a 30-min lockout that has just passed
            $hadPreviousLockout = ($user->lockout_count >= 1 && $user->locked_until && $user->locked_until->isPast());

            // 3. Attempt Authentication
            if (Auth::attempt($credentials)) {
                // Successful login -> completely clear all attempt counters and lockouts
                $user->update([
                    'login_attempts' => 0,
                    'locked_until' => null,
                    'lockout_count' => 0,
                ]);

                $request->session()->regenerate();
                return redirect()->intended('/admin');
            }

            // 4. Failed Login Attempt
            if ($hadPreviousLockout) {
                // User already had 30-min cooldown and failed again! Lock permanently and force OTP reset.
                $user->update([
                    'login_attempts' => $user->login_attempts + 1,
                    'locked_until' => now()->addYears(10),
                    'lockout_count' => 2,
                ]);

                return back()->withErrors([
                    'email' => 'Login failed after your 30-minute cooldown period. Your account is now locked. You must reset your password via Email OTP.',
                ])->with('forced_forgot_password', true)->with('locked_email', $user->email)->withInput();
            }

            $newAttempts = $user->login_attempts + 1;

            if ($newAttempts >= 3) {
                // Trigger 30-minute lockout
                $user->update([
                    'login_attempts' => $newAttempts,
                    'locked_until' => now()->addMinutes(30),
                    'lockout_count' => 1,
                ]);

                return back()->withErrors([
                    'email' => 'You have reached the limit of 3 failed login attempts. Your account is locked for 30 minutes. You can retry after 30 minutes or reset your password now.',
                ])->with('show_forgot_link', true)->with('locked_email', $user->email)->withInput();
            }

            $user->update(['login_attempts' => $newAttempts]);
            $remainingAttempts = 3 - $newAttempts;

            return back()->withErrors([
                'email' => "Invalid email or password. You have {$remainingAttempts} attempt(s) remaining before a 30-minute account lockout.",
            ])->withInput();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function index()
    {
        $posts_count = Post::count();
        $categories_count = Category::count();
        $comments_count = Comment::count();
        $subscribers_count = Newsletter::count();

        $recent_posts = Post::orderBy('created_at', 'desc')->take(5)->get();
        $recent_comments = Comment::with('post')->orderBy('created_at', 'desc')->take(5)->get();

        // Analytics Statistics Queries
        $today = now()->toDateString();
        
        // Views
        $views_today = \DB::table('analytics')->where('date', $today)->value('views') ?? 0;
        $views_month = \DB::table('analytics')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('views') ?? 0;
        
        // Impressions
        $impressions_today = \DB::table('analytics')->where('date', $today)->value('impressions') ?? 0;
        
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $impressions_week = \DB::table('analytics')->whereBetween('date', [$startOfWeek, $endOfWeek])->sum('impressions') ?? 0;
        
        $impressions_month = \DB::table('analytics')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('impressions') ?? 0;

        // Top Posts per Views
        $top_posts = Post::orderBy('view_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'posts_count', 'categories_count', 'comments_count', 'subscribers_count',
            'recent_posts', 'recent_comments',
            'views_today', 'views_month', 'impressions_today', 'impressions_week', 'impressions_month', 'top_posts'
        ));
    }

    public function comments(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Comment::with('post')->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $comments = $query->paginate(20);

        // Counts
        $count_all = Comment::count();
        $count_pending = Comment::where('status', 'pending')->count();
        $count_approved = Comment::where('status', 'approved')->count();
        $count_denied = Comment::where('status', 'denied')->count();

        return view('admin.comments', compact(
            'comments', 'status', 'count_all', 'count_pending', 'count_approved', 'count_denied'
        ));
    }

    public function approveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'approved']);
        
        // Update post comments count
        if ($comment->post) {
            $comment->post->update([
                'comments_count' => $comment->post->comments()->where('status', 'approved')->count()
            ]);
        }

        return redirect()->back()->with('success', 'Comment has been approved and is now visible on the website.');
    }

    public function denyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'denied']);
        
        // Update post comments count
        if ($comment->post) {
            $comment->post->update([
                'comments_count' => $comment->post->comments()->where('status', 'approved')->count()
            ]);
        }

        return redirect()->back()->with('success', 'Comment has been denied and will not appear on the website.');
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $post = $comment->post;
        $comment->delete();

        // Update post comments count
        if ($post) {
            $post->update([
                'comments_count' => $post->comments()->where('status', 'approved')->count()
            ]);
        }

        return redirect()->back()->with('success', 'Comment has been permanently deleted.');
    }

    public function newsletters()
    {
        $subscribers = Newsletter::orderBy('created_at', 'desc')->paginate(30);
        return view('admin.newsletters', compact('subscribers'));
    }

    public function categories(Request $request)
    {
        $categories = Category::withCount('posts')->orderBy('name', 'asc')->get();
        $menus = Menu::all();
        $activeMenuId = $request->input('menu_id') ?: ($menus->first()->id ?? null);
        $activeMenu = $activeMenuId ? Menu::with('items')->find($activeMenuId) : null;
        
        return view('admin.categories', compact('categories', 'menus', 'activeMenu', 'activeMenuId'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        // Safe disassociation of posts before deleting
        \App\Models\Post::where('category_id', $category->id)->update(['category_id' => null]);

        $categoryName = $category->name;
        $category->delete();

        return redirect('/admin/categories')->with('success', "Category '{$categoryName}' deleted successfully!");
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);
        
        $menu = Menu::create($validated);
        return redirect('/admin/categories?menu_id=' . $menu->id)->with('success', "Menu '{$menu->name}' created successfully!");
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menuName = $menu->name;

        // Delete all child menu items
        $menu->items()->delete();
        $menu->delete();

        return redirect('/admin/categories')->with('success', "Menu '{$menuName}' deleted successfully!");
    }

    public function storeMenuItem(Request $request, $menuId)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);
        
        $menu = Menu::findOrFail($menuId);
        $order = ($menu->items()->max('order') ?? 0) + 1;
        
        $menu->items()->create([
            'label' => $validated['label'],
            'url' => $validated['url'],
            'parent_id' => $validated['parent_id'] ?? null,
            'order' => $order,
        ]);
        
        return redirect('/admin/categories?menu_id=' . $menuId)->with('success', 'Menu item added successfully!');
    }

    public function updateMenuItemOrder(Request $request)
    {
        $items = $request->input('items'); // expects array like [{id: 1, order: 1}, {id: 2, order: 2}]
        if (is_array($items)) {
            foreach ($items as $item) {
                MenuItem::where('id', $item['id'])->update(['order' => $item['order']]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function deleteMenuItem(Request $request, $id)
    {
        $item = MenuItem::find($id);
        if (!$item) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }
            return redirect()->back()->with('error', 'Menu item not found.');
        }

        $menuId = $item->menu_id;
        $label = $item->label;

        // Recursively delete children of this menu item
        MenuItem::where('parent_id', $item->id)->delete();
        $item->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Menu item '{$label}' removed successfully!"]);
        }

        return redirect('/admin/categories?menu_id=' . $menuId)->with('success', "Menu item '{$label}' removed successfully!");
    }
}
