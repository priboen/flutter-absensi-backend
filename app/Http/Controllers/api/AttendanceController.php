<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSchedule;
use App\Models\Classes;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function checkin(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'class_id' => 'required|exists:classes,id',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            $class = Classes::findOrFail($validatedData['class_id']);
            $schedule = AttendanceSchedule::where([
                'course_id' => $class->course_id,
                'groupClass_id' => $class->groupClass_id,
                'date' => now()->toDateString(),
                'is_open' => true,
            ])->first();
            if (!$schedule) {
                return response(['message' => 'Tidak ada jadwal presensi yang dibuka.'], 403);
            }
            $existingAttendance = Attendance::where('class_id', $validatedData['class_id'])
                ->where('date', now()->toDateString())
                ->exists();
            if ($existingAttendance) {
                return response(['message' => 'Kamu sudah melakukan Presensi!'], 400);
            }
            $attendance = Attendance::create([
                'class_id' => $validatedData['class_id'],
                'date' => now()->toDateString(),
                'time_in' => now()->toTimeString(),
                'latlong_in' => "{$validatedData['latitude']},{$validatedData['longitude']}",
            ]);
            activity()
                ->causedBy(auth('sanctum')->user())
                ->log('Sudah melakukan presensi datang pada kelas ' . $attendance->class->course->name);
            return response()->json(['message' => 'Presensi berhasil!', 'attendance' => $attendance], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
        }
    }
    public function checkout(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'class_id' => 'required|exists:classes,id',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            $attendance = Attendance::where('class_id', $validatedData['class_id'])
                ->where('date', now()->toDateString())
                ->first();
            if (!$attendance) {
                return response()->json(['message' => 'Kamu belum melakukan presensi datang!'], 400);
            }
            if ($attendance->time_out) {
                return response()->json(['message' => 'Kamu sudah melakukan presensi pulang!'], 400);
            }
            $attendance->update([
                'time_out' => now()->toTimeString(),
                'latlong_out' => "{$validatedData['latitude']},{$validatedData['longitude']}",
            ]);
            activity()
                ->causedBy(auth('sanctum')->user())
                ->log('Sudah melakukan presensi pulang pada kelas ' . $attendance->class->course->name);
            return response()->json(['message' => 'Presensi berhasil!', 'attendance' => $attendance], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
        }
    }
    public function isCheckedin(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'class_id' => 'required|exists:classes,id',
            ]);
            $attendance = Attendance::where('class_id', $validatedData['class_id'])
                ->where('date', now()->toDateString())
                ->first();
            return response()->json(['message' => 'Data presensi', 'attendance' => $attendance], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
        }
    }
    // public function index(Request $request)
    // {
    //     try {
    //         $date = $request->input('date');
    //         $currentUser = $request->user(); // Mendapatkan user yang sedang login

    //         // Query untuk mendapatkan attendance berdasarkan user_id
    //         $query = Attendance::whereHas('class', function ($q) use ($currentUser) {
    //             $q->where('user_id', $currentUser->id); // Filter berdasarkan user_id
    //         });

    //         // Filter berdasarkan tanggal jika diberikan
    //         if ($date) {
    //             $query->where('date', $date);
    //         }

    //         // Mendapatkan hasil
    //         $attendance = $query->get();

    //         return response([
    //             'message' => 'Success',
    //             'data' => $attendance
    //         ], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan server',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function index(Request $request)
{
    try {
        $date = $request->input('date');
        $currentUser = $request->user(); // Mendapatkan user yang sedang login

        // Query untuk mendapatkan attendance dengan nama mata kuliah
        $query = Attendance::with(['class.course']) // Eager load relasi
            ->whereHas('class', function ($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id); // Filter berdasarkan user_id
            });

        // Filter berdasarkan tanggal jika diberikan
        if ($date) {
            $query->where('date', $date);
        }

        // Mendapatkan hasil
        $attendance = $query->get();

        // Format ulang data agar menampilkan nama mata kuliah
        $attendanceData = $attendance->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'classroom' => $item->class->course->classroom->name ?? null,
                'group_class' => $item->class->groupClass->name ?? null,
                'course_name' => $item->class->course->name ?? null, // Nama mata kuliah
                'time_in' => $item->time_in,
                'time_out' => $item->time_out,
                'latlong_in' => $item->latlong_in,
                'latlong_out' => $item->latlong_out,
            ];
        });

        return response([
            'message' => 'Success',
            'data' => $attendanceData
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
