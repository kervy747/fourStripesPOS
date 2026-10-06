<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // SHOW USER LIST
    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH BY NAME OR EMAIL
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // FILTER BY ROLE
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // PAGINATED RESULTS
        $users = $query->orderBy('first_name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // SHOW ADD USER FORM
    public function create()
    {
        return view('admin.users.create');
    }

    // STORE NEW USER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,staff'],
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    // SHOW EDIT USER FORM
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // UPDATE USER
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,staff'],
        ]);

        // ADMIN CANNOT CHANGE OWN ROLE
        if ($user->id === auth()->id() && $validated['role'] !== $user->role) {
            return back()->withErrors(['role' => 'You cannot change your own role.'])->withInput();
        }

        // BLANK PASSWORD KEEPS THE CURRENT ONE
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // ACTIVATE OR DEACTIVATE USER
    public function toggleStatus(User $user)
    {
        // ADMIN CANNOT DEACTIVATE OWN ACCOUNT
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $message = $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.';

        return redirect()->route('users.index')->with('success', $message);
    }
}