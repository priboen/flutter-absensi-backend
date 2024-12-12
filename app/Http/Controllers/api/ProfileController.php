<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function getUserProfile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'message' => 'success',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserProfile(Request $request)
    {
        try {
            $user = $request->user();

            // Validasi
            $request->validate([
                'phone' => 'nullable|string',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info('Validation Passed', $request->all());

            // Update data 'phone' jika dikirim
            if ($request->has('phone')) {
                Log::info('Phone Received', ['phone' => $request->phone]);
                $user->phone = $request->phone;
            }

            // Tangani upload file 'image_url' jika ada
            // if ($request->hasFile('image_url')) {
            //     $path = $request->file('image_url')->store('public/user_images'); // Simpan file di folder `user_images`
            //     $user->image_url = $path;
            // }

            if ($request->hasFile('image_url')) {
                Log::info('Image Received', [$request->file('image_url')]);

                $path = $request->file('image_url')->store('public/user_images');
                Log::info('Image Stored', ['path' => $path]);

                $user->image_url = $path;
            }

            Log::info('Before Save', $user->toArray());
            $user->save();
            Log::info('After Save', $user->toArray());

            // Format URL untuk response (opsional)
            $user->image_url = $user->image_url ? asset(str_replace('public', 'storage', $user->image_url)) : null;

            return response()->json([
                'message' => 'success',
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'failed',
                'data' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateUserPassword(Request $request)
    {
        try {
            $user = $request->user();
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);
            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json([
                    'message' => 'success',
                    'user' => $user
                ], 200);
            } else if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'message' => 'Password Lama Salah',
                ], 401);
            } // cek apakah new_password dan confirm password sama
            else if ($request->new_password != $request->confirm_password) {
                return response()->json([
                    'message' => 'Password Baru dan Konfirmasi Password Baru Tidak Sama',
                ], 401);
            } else {
                return response()->json([
                    'message' => 'Periksa Kembali Password Baru Anda',
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
