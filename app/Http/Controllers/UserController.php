<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }
    public function create()
    {
        return view('pages.users.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'unique_number' => 'required|unique:users,unique_number',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'unique_number' => $request->unique_number,
            'password' => Hash::make($request->password),
            'department' => $request->department,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'unique_number' => $request->unique_number,
            'password' => Hash::make($request->password),
            'department' => $request->department,
        ]);
        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
