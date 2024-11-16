<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AttendanceSchedule;
use App\Models\Course;
use App\Models\GroupClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceSchedule::with(['course', 'groupClass'])->orderBy('id', 'desc');
        $query->when($request->has('course'), function ($q) use ($request) {
            $q->whereHas('course', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->course . '%');
            });
        });

        $schedule = $query->orderBy('id', 'desc')->paginate(10);
        $schedule->getCollection()->transform(function ($sch) {
            $sch->time_start = $sch->time_start ? Carbon::parse($sch->time_start)->format('H:i') : null;
            $sch->time_end = $sch->time_end ? Carbon::parse($sch->time_end)->format('H:i') : null;
            return $sch;
        });
        return view('pages.attendance-schedules.index', compact('schedule'));
    }

    public function create()
    {
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.attendance-schedules.create', compact(['course', 'groupClass']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'groupClass_id' => 'required|exists:group_classes,id',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $count = $request->count;
        $startDate = Carbon::parse($request->date);

        for ($i = 0; $i < $count; $i++) {
            AttendanceSchedule::create([
                'course_id' => $request->course_id,
                'groupClass_id' => $request->groupClass_id,
                'date' => $startDate->copy()->addWeeks($i)->toDateString(),
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
                'is_open' => false,
            ]);
        }

        return redirect()->route('attendance-schedules.index')->with('success', 'Attendance schedules created successfully');
    }


    public function edit($id)
    {
        $schedule = AttendanceSchedule::find($id);
        if (!$schedule) {
            return redirect()->route('attendance-schedules.index')->with('error', 'Attendance schedule not found.');
        }
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.attendance-schedules.edit', compact(['schedule', 'course', 'groupClass']));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'groupClass_id' => 'required|exists:group_classes,id',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $schedule = AttendanceSchedule::findOrFail($id);
        $schedule->course_id = $request->course_id;
        $schedule->groupClass_id = $request->groupClass_id;
        $schedule->date = $request->date;
        $schedule->time_start = $request->time_start;
        $schedule->time_end = $request->time_end;

        $schedule->save();

        return redirect()->route('attendance-schedules.index')->with('success', 'Jadwal presensi berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $schedule = AttendanceSchedule::find($id);
        $schedule->delete();
        return redirect()->route('attendance-schedules.index')->with('success', 'Course Attendance deleted successfully');
    }

    public function toggleStatus(Request $request)
    {
        $schedule = AttendanceSchedule::findOrFail($request->id);
        $schedule->is_open = !$schedule->is_open;
        $schedule->save();
        $message = $schedule->is_open ? 'Presensi sudah dibuka!' : 'Presensi sudah ditutup!';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'is_open' => $schedule->is_open
        ]);
    }
}
