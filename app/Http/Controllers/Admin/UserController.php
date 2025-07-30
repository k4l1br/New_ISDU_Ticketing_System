<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    
    public function index(Request $request)
{
    $query = User::query();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->paginate(10); // Optional pagination
    return view('admin.users.index', compact('users'));
}


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',

        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
    'name' => $request->name,
    'email' => $request->email,
    'username' => $request->username, // ✅ Add this
    'role' => $request->role,
    'password' => Hash::make($request->password),
    ]);


    return redirect()->route('admin.users.index')
           ->with('success', 'User created successfully.');
    }
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string', 'in:admin,super_admin'],
        ];

        // Add password validation only if password is provided
        if ($request->filled('password')) {
            $validationRules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $request->validate($validationRules);

        $user->update($request->only('name', 'email', 'role'));

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
               ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
               ->with('success', 'User deleted successfully.');
    }

    
}