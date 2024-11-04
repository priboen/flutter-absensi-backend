<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    public function getCoursesByStudent(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $courses = Classes::with(['course.classroom'])
            ->where('user_id', $user->id)
            ->get();
        return response()->json([
            'data' => $courses
        ], 200);
    }
}
