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

        $dataMK = $courses->map(function ($course) {
            $timeIn = \Carbon\Carbon::parse($course->course->time_in);
            $totalMinutes = $course->course->credits * 50;
            $timeOut = $timeIn->copy()->addMinutes($totalMinutes);
            return [
                'id' => $course->id,
                'course' => $course->course->name,
                'course_code' => $course->course->courses_code,
                'credits' => $course->course->credits,
                'classroom' => $course->course->classroom->name,
                'building_name' => $course->course->classroom->building_name,
                'time_in' => $timeIn->format('H:i'),
                'time_out' => $timeOut->format('H:i'),
                'latitude' => $course->course->classroom->latitude,
                'longitude' => $course->course->classroom->longitude,
            ];
        });
        return response()->json([
            'message' => 'success',
            'data' => $dataMK
        ], 200);
    }
}
