<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['class', 'classroom']);
        $query->when($request->has('classroom'), function ($q) use ($request) {
            $q->whereHas('classroom', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->course . '%');
            });
        });

        $query->when($request->has('class'), function ($q) use ($request) {
            $q->whereHas('class', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->class . '%');
            });
        });

        $attendances = $query->orderBy('id', 'desc')->paginate(10);
        $attendances->getCollection()->transform(function ($attendance) {
            $attendance->time_in = $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i') : null;
            $attendance->time_out = $attendance->time_out ? Carbon::parse($attendance->time_out)->format('H:i') : null;
            return $attendance;
        });
        return view('pages.attendances.index', compact('attendances'));
    }
}
