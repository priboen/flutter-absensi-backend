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
            $schedule = AttendanceSchedule::where('groupClass_id', $class->groupClass_id)
                ->where('date', now()->toDateString())
                ->where('is_open', true)
                ->first();
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
            return response(['message' => 'Checkin success', 'attendance' => $attendance], 200);
        } catch (ValidationException $e) {
            return response(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'Data tidak ditemukan'], 404);
        } catch (Exception $e) {
            return response(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
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
                return response(['message' => 'Kamu belum melakukan presensi!'], 400);
            }
            $attendance->update([
                'time_out' => now()->toTimeString(),
                'latlong_out' => "{$validatedData['latitude']},{$validatedData['longitude']}",
            ]);
            activity()
                ->causedBy(auth('sanctum')->user())
                ->log('Sudah melakukan presensi pulang pada kelas ' . $attendance->class->course->name);
            return response(['message' => 'Terima kasih sudah berkuliah hari ini!', 'attendance' => $attendance], 200);
        } catch (ValidationException $e) {
            return response(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
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
            return response([
                'checkin' => $attendance ? true : false,
                'checkout' => $attendance && $attendance->time_out ? true : false,
            ], 200);
        } catch (ValidationException $e) {
            return response(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
        }
    }
}
