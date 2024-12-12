<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

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
        try {
            $permission = Permission::find($id);
            $permission->update([
                'is_approved' => $request->is_approved,
            ]);
            $str = $request->is_approved == 1 ? 'di setujui' : 'di tolak';
            $this->sendNotificationToUser($permission->class->user->id, 'Izin mata kuliah ' . $permission->class->course->name . ' ' . $str);
            activity()->causedBy(Auth::user())->log('Mengubah status perizinan ' . $permission->class->user->name);
            return redirect()->route('permissions.index')->with('success', 'Status perizinan berhasil diubah');
        } catch (Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Gagal mengubah status perizinan. Silakan coba lagi.' . $e->getMessage());
        }
    }
    public function sendNotificationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_token;

        $messaging = app('firebase.messaging');
        $notification = Notification::create('Status Izin', $message);

        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => $notification,
        ]);
        $messaging->send($message);
    }
}
