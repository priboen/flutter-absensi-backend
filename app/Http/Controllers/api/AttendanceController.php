<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSchedule;
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

        $schedule = AttendanceSchedule::where('groupClass_id', $request->class_id)
            ->whereDate('date', now()->toDateString())
            ->where('is_open', true)
            ->first();

        if (!$schedule) {
            return response(['message' => 'Tidak ada jadwal presensi yang dibuka.'], 403);
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

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance
        ], 200);
    }

    // Checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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

        return response([
            'message' => 'Terima kasih sudah berkuliah hari ini!',
            'attendance' => $attendance
        ], 200);
    }

    public function isCheckedin(Request $request)
    {
        $attendance = Attendance::where('class_id', $request->class_id)->where('date', date('Y-m-d'))->first();

        $isCheckout = $attendance ? $attendance->time_out : false;

        return response([
            'checkin' => $attendance ? true : false,
            'checkout' => $isCheckout ? true : false,
        ], 200);
    }
}
