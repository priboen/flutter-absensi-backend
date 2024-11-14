<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('class')->when(request('search'), function ($query) {
            $query->where('reason', 'like', '%' . request('search') . '%');
        })->latest()->paginate(10);

        activity()->causedBy(Auth::user())->log('Menampilkan halaman perizinan');
        return view('pages.permissions.index', compact('permissions'));
    }
    public function show($id)
    {
        $permission = Permission::with('class')->find($id);
        Activity()->causedBy(Auth::user())->log('Menampilkan detail perizinan ' . $permission->users->name);
        return view('pages.permissions.show', compact('permission'));
    }
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('pages.permissions.edit', compact('permission'));
    }
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->is_approved = $request->is_approved;
        $oldData = $permission->getOriginal();
        $newData = $permission->getAttributes();
        $str = $request->is_approved == 1 ? 'Disetujui' : 'Ditolak';
        $permission->save();
        activity()
            ->causedBy(Auth::user())
            ->log($str . ' perizinan ' . $permission->users->name);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
}
