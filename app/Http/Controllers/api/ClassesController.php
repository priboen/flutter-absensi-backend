<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    public function getCoursesByStudent(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $classes = Classes::with([
                'course.classroom',
                'course.schedules',
                'groupClass'
            ])->where('user_id', $user->id)
                ->get();
            $dataMK = $classes->map(function ($class) {
                $schedule = Schedule::where('course_id', $class->course_id)
                    ->where('groupClass_id', $class->groupClass->id)->first();
                return [
                    'id' => $class->id,
                    'course' => $class->course->name,
                    'course_code' => $class->course->courses_code,
                    'groupClass' => $class->groupClass->name,
                    'credits' => $class->course->credits,
                    'classroom' => $class->course->classroom->name,
                    'building_name' => $class->course->classroom->building_name,
                    'latitude' => $class->course->classroom->latitude,
                    'longitude' => $class->course->classroom->longitude,
                    'radius' => $class->course->classroom->radius,
                    'time_in' => $schedule ? Carbon::parse($schedule->time_in)->format('H:i') : null,
                    'time_out' => $schedule ? Carbon::parse($schedule->time_out)->format('H:i') : null,
                ];
            });
            return response()->json([
                'message' => 'success',
                'data' => $dataMK
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
