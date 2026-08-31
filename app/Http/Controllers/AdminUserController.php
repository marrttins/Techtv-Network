<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['super-admin', 'sub-admin', 'author'])],
            'permissions' => 'nullable|array'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Ensure permissions is at least an empty array if not provided
        $validated['permissions'] = $validated['permissions'] ?? [];

        User::create($validated);

        return redirect('/admin/users')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super-admin', 'sub-admin', 'author'])],
            'permissions' => 'nullable|array'
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        // Prevent a user from removing their own super-admin role if they are the only one
        if ($user->role === 'super-admin' && $validated['role'] !== 'super-admin') {
            $superAdminsCount = User::where('role', 'super-admin')->count();
            if ($superAdminsCount <= 1) {
                return back()->withErrors(['role' => 'Cannot change role. You are the only super-admin.']);
            }
        }

        $validated['permissions'] = $validated['permissions'] ?? [];

        $user->update($validated);

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }

        if ($user->role === 'super-admin') {
            $superAdminsCount = User::where('role', 'super-admin')->count();
            if ($superAdminsCount <= 1) {
                return back()->withErrors(['error' => 'Cannot delete the only super-admin.']);
            }
        }

        $user->delete();

        return redirect('/admin/users')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the profile edit form for the currently authenticated admin.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the authenticated admin's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Your profile information has been updated successfully.');
    }

    /**
     * Update the authenticated admin's password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Your password has been changed successfully.');
    }
}
