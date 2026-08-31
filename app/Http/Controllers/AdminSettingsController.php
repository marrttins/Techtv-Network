<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminSettingsController extends Controller
{

    /**
     * Show the form for editing site settings.
     */
    public function edit()
    {
        $settings = DB::table('settings')->pluck('value', 'key')->all();
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the site settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'logo_path' => 'nullable|string',
        ]);

        $logoPath = DB::table('settings')->where('key', 'site_logo')->value('value') ?? 'assets/img/logo.jpg';

        if ($request->hasFile('logo')) {
            // If local upload, check and clean old uploaded logos to save space
            if ($logoPath && str_starts_with($logoPath, 'uploads/') && File::exists(public_path($logoPath))) {
                File::delete(public_path($logoPath));
            }
            
            $logoFile = $request->file('logo');
            $name = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $logoFile->getClientOriginalName());
            $destinationPath = public_path('/uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $logoFile->move($destinationPath, $name);
            $logoPath = 'uploads/' . $name;
        } elseif ($request->filled('logo_path')) {
            // Selected from Media Library
            $logoPath = $request->logo_path;
        }

        // Save keys
        DB::table('settings')->updateOrInsert(
            ['key' => 'site_title'],
            ['value' => $validated['site_title'], 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'site_logo'],
            ['value' => $logoPath, 'updated_at' => now()]
        );

        return back()->with('success', 'Site settings updated successfully!');
    }
}
