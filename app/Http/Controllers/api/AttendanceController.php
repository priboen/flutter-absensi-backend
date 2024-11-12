<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id', // Pastikan class_id ada
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $existingAttendance = Attendance::where('class_id', $request->class_id)
            ->where('date', date('Y-m-d'))
            ->first();

        if ($existingAttendance) {
            return response(['message' => 'Kamu sudah melakukan Presensi!'], 400);
        }

        $attendance = new Attendance;
        $attendance->class_id = $request->class_id;
        $attendance->date = now()->toDateString();
        $attendance->time_in = now()->toTimeString();
        $attendance->latlong_in = $request->latitude . ',' . $request->longitude;

        $attendance->save();
        activity()->causedBy(auth('sanctum')->user())->log('Sudah melakukan presensi datang pada kelas ' . $attendance->class->course->name);

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance
        ], 200);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $attendance = Attendance::where('class_id', $request->class_id)
            ->where('date', date('Y-m-d'))
            ->first();

        if (!$attendance) {
            return response([
                'message' => 'Kamu belum melakukan presensi!',
            ], 400);
        }

        $attendance->time_out = now()->toTimeString();
        $attendance->latlong_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        activity()->causedBy(auth('sanctum')->user())->log('Sudah melakukan presensi pulang pada kelas ' . $attendance->class->course->name);

        return response([
            'message' => 'Terima kasih sudah berkuliah hari ini!',
            'attendance' => $attendance
        ], 200);
    }

    public function isCheckedin(Request $request)
    {
        $attendance = Attendance::where('class_id', $request->class_id)->where('date', date('Y-m-d'))->first();
        return response([
            'attendances' => $attendance ? true : false,
        ], 200);
    }
}
