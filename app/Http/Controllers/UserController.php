<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
    $imagePath = $user->image_url;
    return view('pages.users.show', compact('user', 'imagePath'));
}
    public function create()
    {
        return view('pages.users.create');
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'unique_number' => 'required|unique:users,unique_number',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $data = $request->all();
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('user_images');
                $data['image_url'] = basename($imagePath);
            }
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $admin = Auth::user();
            activity()->causedBy($admin)->performedOn($user)->log('Created new user: ' . $request->name);
            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to create user. Please try again.' . $e->getMessage());
        }
    }
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $oldData = $user->getOriginal();
            $newData = $request->all();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'unique_number' => $request->unique_number,
                'department' => $request->department,
            ]);
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            if ($request->hasFile('image_url')) {
                if ($user->image_url && Storage::exists('user_images/' . $user->image_url)) {
                    Storage::delete('user_images/' . $user->image_url);
                }
                $imagePath = $request->file('image_url')->store('user_images');
                $user->update(['image_url' => basename($imagePath)]);
            }
            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties(['old_data' => $oldData, 'new_data' => $newData])
                ->log('Updated user details: ' . $user->name);
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to update user. Please try again.' . $e->getMessage());
        }
    }
    public function destroy(User $user)
    {
        $user->delete();
        activity()->causedBy(Auth::user())->performedOn($user)->log('Deleted user: ' . $user->name);
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
