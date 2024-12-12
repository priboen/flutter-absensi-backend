<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        try {
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
            $permission->is_approved = $request->has('is_approved') ? $request->is_approved : null;

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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {

        try{
            $currentUser = $request->user();
        $query = Permission::with(['class.course'])->whereHas('class', function ($q) use ($currentUser) {
            $q->where('user_id', $currentUser->id);
        });

        $permissions = $query->get();

        $permissionData = $permissions->map(function ($item) {
            return [
                'id' => $item->id,
                'course_name' => $item->class->course->name ?? null,
                'date_permission' => $item->date_permission,
                'reason' => $item->reason,
                'is_approved' => $item->is_approved,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $permissionData
        ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'Terjadi kesalahan server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
