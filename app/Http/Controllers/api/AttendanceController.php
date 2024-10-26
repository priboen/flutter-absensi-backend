<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id', // Pastikan class_id ada
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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

        // Simpan presensi baru
        $attendance = new Attendance;
        $attendance->class_id = $request->class_id; // Ambil class_id dari request Ambil classroom_id dari class_id
        $attendance->date = now()->toDateString(); // Tanggal saat ini
        $attendance->time_in = now()->toTimeString(); // Waktu saat ini
        $attendance->latlong_in = $request->latitude . ',' . $request->longitude; // Format latlong

        $attendance->save();

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance
        ], 200);
    }

    public function isCheckin(Request $request)
    {
        $attendance = Attendance::where('class_id', $request->class_id)
            ->where('user_id', Auth::user()->id)
            ->whereDate('date', date('Y-m-d'))
            ->first();

        $isCheckout = $attendance ? $attendance->time_out : false;

        return response([
            'checkedin' => $attendance ? true : false,
            'checkedout' => $isCheckout ? true : false,
        ], 200);
    }
}
