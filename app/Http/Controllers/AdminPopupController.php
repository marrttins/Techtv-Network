<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Popup;
use Illuminate\Support\Facades\File;

use App\Models\Category;
use App\Models\Post;

class AdminPopupController extends Controller
{
    /**
     * Display a listing of the popups.
     */
    public function index()
    {
        $popups = Popup::orderBy('created_at', 'desc')->get();
        return view('admin.popups.index', compact('popups'));
    }

    /**
     * Show the form for creating a new popup.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $posts = Post::where('status', 'publish')->orderBy('published_at', 'desc')->take(60)->get();
        return view('admin.popups.create', compact('categories', 'posts'));
    }

    /**
     * Store a newly created popup in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'display_type' => 'required|string|in:all_pages,specific_page',
            'specific_page_path' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if (!$request->hasFile('image') && !$request->filled('image_path')) {
            return back()->withErrors(['image' => 'Please upload an image or select one from the Media Library.'])->withInput();
        }

        $targetPagePath = $request->input('specific_page_path', '');
        if ($validated['display_type'] === 'specific_page') {
            if ($targetPagePath === '' || $targetPagePath === '/' || $targetPagePath === 'home' || $targetPagePath === 'index') {
                $targetPagePath = '/';
            }
        } else {
            $targetPagePath = null;
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

        // If this is set to active, deactivate all other popups (since we only show one at a time)
        $isActive = $request->has('is_active') ? (bool) $request->is_active : true;
        if ($isActive) {
            Popup::where('is_active', true)->update(['is_active' => false]);
        }

        Popup::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'link' => $validated['link'],
            'display_type' => $validated['display_type'],
            'specific_page_path' => $targetPagePath,
            'is_active' => $isActive,
        ]);

        return redirect('/admin/popups')->with('success', 'Popup created successfully!');
    }

    /**
     * Show the form for editing the specified popup.
     */
    public function edit($id)
    {
        $popup = Popup::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $posts = Post::where('status', 'publish')->orderBy('published_at', 'desc')->take(60)->get();
        return view('admin.popups.edit', compact('popup', 'categories', 'posts'));
    }

    /**
     * Update the specified popup in storage.
     */
    public function update(Request $request, $id)
    {
        $popup = Popup::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'display_type' => 'required|string|in:all_pages,specific_page',
            'specific_page_path' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $targetPagePath = $request->input('specific_page_path', '');
        if ($validated['display_type'] === 'specific_page') {
            if ($targetPagePath === '' || $targetPagePath === '/' || $targetPagePath === 'home' || $targetPagePath === 'index') {
                $targetPagePath = '/';
            }
        } else {
            $targetPagePath = null;
        }

        $imagePath = $popup->image_path;

        if ($request->hasFile('image')) {
            // Delete old image if it was uploaded locally
            if ($popup->image_path && str_starts_with($popup->image_path, 'uploads/') && File::exists(public_path($popup->image_path))) {
                File::delete(public_path($popup->image_path));
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
        if ($isActive && !$popup->is_active) {
            // Deactivate all others
            Popup::where('is_active', true)->where('id', '!=', $id)->update(['is_active' => false]);
        }

        $popup->update([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'link' => $validated['link'],
            'display_type' => $validated['display_type'],
            'specific_page_path' => $targetPagePath,
            'is_active' => $isActive,
        ]);

        return redirect('/admin/popups')->with('success', 'Popup updated successfully!');
    }

    /**
     * Remove the specified popup from storage.
     */
    public function destroy($id)
    {
        $popup = Popup::findOrFail($id);
        
        if ($popup->image_path && str_starts_with($popup->image_path, 'uploads/') && File::exists(public_path($popup->image_path))) {
            File::delete(public_path($popup->image_path));
        }

        $popup->delete();

        return redirect('/admin/popups')->with('success', 'Popup deleted successfully!');
    }
}
