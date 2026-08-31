<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\File;

class AdminAdController extends Controller
{
    /**
     * Get available ad slots grouped by page.
     */
    public static function getSlots()
    {
        return [
            'home' => [
                'title' => '🏠 Homepage Placements',
                'slots' => [
                    'home_header_leaderboard' => ['label' => 'Top Header Banner', 'size' => '728×90 / 970×150', 'desc' => 'Placed at the top of the homepage.'],
                    'home_under_ticker' => ['label' => 'Under News Ticker Banner', 'size' => '728×90 / 970×250', 'desc' => 'Full-width banner directly under the breaking news ticker.'],
                    'home_under_slider' => ['label' => 'Under Hero Grid Banner', 'size' => '728×90 / 970×250', 'desc' => 'Placed right under the 3-column top featured grid.'],
                    'home_sidebar_rect' => ['label' => 'Sidebar Medium Rectangle', 'size' => '300×250', 'desc' => 'High-visibility sidebar banner.'],
                    'home_sidebar_halfpage' => ['label' => 'Sidebar Half Page / Skyscraper', 'size' => '300×600', 'desc' => 'Tall high-impact sticky sidebar ad.'],
                    'home_mid_leaderboard' => ['label' => 'Mid-Page Content Banner', 'size' => '728×90', 'desc' => 'Between homepage category blocks.'],
                    'home_footer_leaderboard' => ['label' => 'Footer Bottom Leaderboard', 'size' => '728×90', 'desc' => 'Placed right above the site footer.'],
                ]
            ],
            'post' => [
                'title' => '📝 Single Blog Post Placements',
                'slots' => [
                    'post_header_leaderboard' => ['label' => 'Article Top Header Banner', 'size' => '728×90 / 970×150', 'desc' => 'Placed above the single post title & header.'],
                    'post_in_article' => ['label' => 'In-Article Mid-Content Ad', 'size' => '728×90 / 336×280', 'desc' => 'Embedded within the article body text.'],
                    'post_sidebar_rect' => ['label' => 'Sidebar Medium Rectangle', 'size' => '300×250', 'desc' => 'Article sidebar ad unit.'],
                    'post_sidebar_halfpage' => ['label' => 'Sidebar Half Page Ad', 'size' => '300×600', 'desc' => 'Article sticky sidebar tall ad.'],
                    'post_footer_leaderboard' => ['label' => 'Article Bottom Leaderboard', 'size' => '728×90', 'desc' => 'Placed above comments & related stories.'],
                ]
            ],
            'category' => [
                'title' => '📁 Category & Archive Page Placements',
                'slots' => [
                    'category_header_leaderboard' => ['label' => 'Category Top Header Banner', 'size' => '728×90 / 970×150', 'desc' => 'Placed above the category title.'],
                    'category_in_feed' => ['label' => 'Between Archive Posts Banner', 'size' => '728×90', 'desc' => 'Inserted after the 3rd story in the feed.'],
                    'category_sidebar_rect' => ['label' => 'Sidebar Medium Rectangle', 'size' => '300×250', 'desc' => 'Category sidebar ad unit.'],
                    'category_sidebar_halfpage' => ['label' => 'Sidebar Half Page Ad', 'size' => '300×600', 'desc' => 'Category sticky sidebar tall ad.'],
                    'category_footer_leaderboard' => ['label' => 'Category Bottom Leaderboard', 'size' => '728×90', 'desc' => 'Placed above the footer on category pages.'],
                ]
            ],
            'global' => [
                'title' => '🌐 Global (All Pages)',
                'slots' => [
                    'global_header_leaderboard' => ['label' => 'Global Header Leaderboard', 'size' => '970×150 / 728×90', 'desc' => 'Shows on every page across the entire website.'],
                    'global_footer_leaderboard' => ['label' => 'Global Footer Leaderboard', 'size' => '728×90', 'desc' => 'Shows at the bottom of every page across the website.'],
                ]
            ]
        ];
    }

    /**
     * Display a listing of the ads.
     */
    public function index(Request $request)
    {
        $currentPage = $request->input('page_filter', 'all');

        $query = Ad::orderBy('created_at', 'desc');

        if ($currentPage && $currentPage !== 'all') {
            $query->where('page', $currentPage);
        }

        $ads = $query->get();

        // Counts
        $count_all = Ad::count();
        $count_home = Ad::where('page', 'home')->count();
        $count_post = Ad::where('page', 'post')->count();
        $count_category = Ad::where('page', 'category')->count();
        $count_global = Ad::where('page', 'global')->count();

        $slotsConfig = self::getSlots();

        return view('admin.ads.index', compact(
            'ads', 'currentPage', 'count_all', 'count_home', 'count_post', 'count_category', 'count_global', 'slotsConfig'
        ));
    }

    /**
     * Show the form for creating a new ad.
     */
    public function create()
    {
        $slotsConfig = self::getSlots();
        return view('admin.ads.create', compact('slotsConfig'));
    }

    /**
     * Store a newly created ad in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'page' => 'required|string|in:home,post,category,global',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if (!$request->hasFile('image') && !$request->filled('image_path')) {
            return back()->withErrors(['image' => 'Please upload an image or select one from the Media Library.'])->withInput();
        }

        $imagePath = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('/uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $name);
            $imagePath = 'uploads/' . $name;
        } else {
            $imagePath = $request->image_path;
        }

        $isActive = $request->has('is_active') ? (bool) $request->is_active : true;

        // If activating, deactivate other ads on the exact same page & slot
        if ($isActive) {
            Ad::where('page', $validated['page'])
              ->where('location', $validated['location'])
              ->where('is_active', true)
              ->update(['is_active' => false]);
        }

        Ad::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'link' => $validated['link'],
            'page' => $validated['page'],
            'location' => $validated['location'],
            'is_active' => $isActive,
        ]);

        return redirect('/admin/ads?page_filter=' . $validated['page'])->with('success', 'Ad banner created and configured successfully!');
    }

    /**
     * Show the form for editing the specified ad.
     */
    public function edit($id)
    {
        $ad = Ad::findOrFail($id);
        $slotsConfig = self::getSlots();
        return view('admin.ads.edit', compact('ad', 'slotsConfig'));
    }

    /**
     * Update the specified ad in storage.
     */
    public function update(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'page' => 'required|string|in:home,post,category,global',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $ad->image_path;

        if ($request->hasFile('image')) {
            if ($ad->image_path && str_starts_with($ad->image_path, 'uploads/') && File::exists(public_path($ad->image_path))) {
                File::delete(public_path($ad->image_path));
            }
            $image = $request->file('image');
            $name = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('/uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $name);
            $imagePath = 'uploads/' . $name;
        } elseif ($request->filled('image_path')) {
            $imagePath = $request->image_path;
        }

        $isActive = $request->has('is_active');

        // If activating, deactivate other ads on the exact same page & slot
        if ($isActive) {
            Ad::where('page', $validated['page'])
              ->where('location', $validated['location'])
              ->where('id', '!=', $id)
              ->where('is_active', true)
              ->update(['is_active' => false]);
        }

        $ad->update([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'link' => $validated['link'],
            'page' => $validated['page'],
            'location' => $validated['location'],
            'is_active' => $isActive,
        ]);

        return redirect('/admin/ads?page_filter=' . $validated['page'])->with('success', 'Ad banner updated successfully!');
    }

    /**
     * Remove the specified ad from storage.
     */
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        
        if ($ad->image_path && str_starts_with($ad->image_path, 'uploads/') && File::exists(public_path($ad->image_path))) {
            File::delete(public_path($ad->image_path));
        }

        $page = $ad->page;
        $ad->delete();

        return redirect('/admin/ads?page_filter=' . $page)->with('success', 'Ad deleted successfully!');
    }
}
