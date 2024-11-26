<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $loginData['email'])->first();

        if (!$user) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        if (!Hash::check($loginData['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        activity()->causedBy($user)->log('Melakukan Log In pada Aplikasi Mobile');

        return response(['user' => $user, 'token' => $token], 200);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        activity()->causedBy($request->user())->log('Melakukan Log Out pada Aplikasi Mobile');

        return response(['message' => 'Logged out'], 200);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'face_embedding' => 'required',
        ]);

        $user = $request->user();
        $face_embedding = $request->face_embedding;
        $user->face_embedding = $face_embedding;
        $user->save();
        activity()->causedBy($user)->log('Mendaftarkan face embbeding');

        return response([
            'message' => 'Profile updated',
            'user' => $user,
        ], 200);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response([
            'message' => 'FCM token updated',
        ], 200);
    }
}
