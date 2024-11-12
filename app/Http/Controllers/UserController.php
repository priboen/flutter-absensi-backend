<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function show()
    {
        $user = Auth::user();
        Activity::create([
            'description' => 'User created a new record',
            'causer_id' => Auth::id(), // ID pengguna yang melakukan aktivitas
            'causer_type' => get_class(Auth::user()), // Tipe model pengguna
        ]);
        return view('pages.users.show', compact('user'));
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'unique_number' => $request->unique_number,
            'password' => Hash::make($request->password),
            'department' => $request->department,
        ]);
        $admin = Auth::user();
        activity()->causedBy($admin)->performedOn($user)->log('Created new user: ' . $request->name);
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

        $oldData = $user->getOriginal();
        $newData = $request->all();

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

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['old_data' => $oldData, 'new_data' => $newData])
            ->log('Updated user details: ' . $user->name);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        activity()->causedBy(Auth::user())->performedOn($user)->log('Deleted user: ' . $user->name);
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
