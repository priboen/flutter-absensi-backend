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
        $permissions = Permission::with('class')->find($id);
        activity()->causedBy(Auth::user())->log('Menampilkan detail perizinan ' . $permissions->class->user->name);
        return view('pages.permissions.show', compact('permissions'));
    }
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('pages.permissions.edit', compact('permission'));
    }
    public function update(Request $request, $id)
    {
        $permissions = Permission::find($id);
        $permissions->is_approved = $request->is_approved;
        $oldData = $permissions->getOriginal();
        $newData = $permissions->getAttributes();
        $str = $request->is_approved == 1 ? 'Disetujui' : 'Ditolak';
        $permissions->save();
        activity()->causedBy(Auth::user())->log('Mengubah status perizinan ' . $permissions->class->user->name . ' dari ' . $oldData['is_approved'] . ' menjadi ' . $newData['is_approved']);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
}
