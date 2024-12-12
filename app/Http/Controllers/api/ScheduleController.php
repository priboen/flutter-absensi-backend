<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function getScheduleByUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            $classes = Classes::with(['groupClass'])
                ->where('user_id', $user->id)
                ->get();
            if ($classes->isEmpty()) {
                return response()->json([
                    'message' => 'No classes found for this user',
                ], 404);
            }
            $classCombinations = $classes->map(function ($class) {
                return [
                    'course_id' => $class->course_id,
                    'groupClass_id' => $class->groupClass_id,
                ];
            });
            $schedules = Schedule::with(['course.classroom', 'groupClass'])
                ->where(function ($query) use ($classCombinations) {
                    foreach ($classCombinations as $combination) {
                        $query->orWhere(function ($subQuery) use ($combination) {
                            $subQuery->where('course_id', $combination['course_id'])
                                ->where('groupClass_id', $combination['groupClass_id']);
                        });
                    }
                })
                ->get();
            if ($schedules->isEmpty()) {
                return response()->json([
                    'message' => 'No schedules found for this user',
                ], 404);
            }
            $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $formattedSchedules = collect($schedules)->map(function ($schedule) {
                return [
                    'day' => $schedule->day,
                    'classroom' => $schedule->course->classroom->name ?? 'N/A',
                    'group_class' => $schedule->groupClass->name ?? 'N/A',
                    'course_name' => $schedule->course->name ?? 'N/A',
                    'time_in' => $schedule->time_in ? Carbon::parse($schedule->time_in)->format('H:i') : null,
                    'time_out' => $schedule->time_out ? Carbon::parse($schedule->time_out)->format('H:i') : null,
                ];
            })->sortBy([
                function ($schedule) use ($dayOrder) {
                    return array_search($schedule['day'], $dayOrder);
                },
                ['course_name', 'asc'],
                ['group_class', 'asc'],
                ['time_in', 'asc'],
            ]);
            $groupedSchedules = $formattedSchedules->groupBy('day')->map(function ($schedulesByDay, $day) {
                return [
                    'day' => $day,
                    'schedules' => collect($schedulesByDay)->values(),
                ];
            })->sortBy(function ($schedule) use ($dayOrder) {
                return array_search($schedule['day'], $dayOrder);
            })->values();
            return response()->json([
                'message' => 'Schedules retrieved successfully',
                'data' => $groupedSchedules,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTodayUserSchedule()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            $day = Carbon::now()->locale('id')->isoFormat('dddd');
            $classes = Classes::with(['groupClass'])
                ->where('user_id', $user->id)
                ->get();
            if ($classes->isEmpty()) {
                return response()->json([
                    'message' => 'No classes found for this user',
                ], 404);
            }
            $classCombinations = $classes->map(function ($class) {
                return [
                    'course_id' => $class->course_id,
                    'groupClass_id' => $class->groupClass_id,
                ];
            });
            $schedules = Schedule::with(['course.classroom', 'groupClass'])
                ->where(function ($query) use ($classCombinations) {
                    foreach ($classCombinations as $combination) {
                        $query->orWhere(function ($subQuery) use ($combination) {
                            $subQuery->where('course_id', $combination['course_id'])
                                ->where('groupClass_id', $combination['groupClass_id']);
                        });
                    }
                })
                ->where('day', $day)
                ->get();
            if ($schedules->isEmpty()) {
                return response()->json([
                    'message' => 'No schedules found for this user on ' . $day,
                ], 404);
            }
            $formattedSchedules = $schedules->map(function ($schedule) {
                return [
                    'day' => $schedule->day,
                    'classroom' => $schedule->course->classroom->name ?? 'N/A',
                    'group_class' => $schedule->groupClass->name ?? 'N/A',
                    'course_name' => $schedule->course->name ?? 'N/A',
                    'time_in' => $schedule->time_in ? Carbon::parse($schedule->time_in)->format('H:i') : null,
                    'time_out' => $schedule->time_out ? Carbon::parse($schedule->time_out)->format('H:i') : null,
                ];
            });
            return response()->json([
                'message' => 'Schedules retrieved successfully',
                'data' => $formattedSchedules,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
