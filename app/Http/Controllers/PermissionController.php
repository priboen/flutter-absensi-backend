<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('class')->when(request('search'), function ($query) {
            $query->where('reason', 'like', '%' . request('search') . '%');
        })->latest()->paginate(10);

        return view('pages.permissions.index', compact('permissions'));
    }
    public function show($id)
    {
        $permission = Permission::with('class')->find($id);
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
        $str = $request->is_approved == 1 ? 'Disetujui' : 'Ditolak';
        $permission->save();
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
}
