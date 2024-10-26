<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'date_permission' => 'required',
            'reason' => 'required',
        ]);
        $permission = new Permission();
        $permission->class_id = $request->class_id;
        $permission->date_permission = $request->date_permission;
        $permission->reason = $request->reason;
        $permission->image = $request->image;
        $permission->is_approved = 0;

        if ($request->hasFile('image')) {
            $request->file('image')->move('images/permission/', $request->file('image')->getClientOriginalName());
            $permission->image = $request->file('image')->getClientOriginalName();
            $permission->save();
        }
        $permission->save();

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ], 200);
    }
}
