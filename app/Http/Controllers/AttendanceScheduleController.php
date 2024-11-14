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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        AttendanceSchedule::create([
            'course_id' => $request->course_id,
            'groupClass_id' => $request->groupClass_id,
            'date' => $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'is_open' => false,
        ]);

        return redirect()->route('attendance-schedules.index')->with('success', 'Attendance schedule created successfully');
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

    public function update(Request $request, AttendanceSchedule $schedule)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'groupClass_id' => 'required|exists:group_classes,id'
        ]);
        $schedule->update([
            'course_id' => $request->course_id,
            'groupClass_id' => $request->groupClass_id,
            'date' => $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'is_open' => $request->is_open,
        ]);
        return redirect()->route('attendance-schedules.index')->with('success', 'Course Attendance updated successfully');
    }
    public function destroy(AttendanceSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('attendance-schedules.index')->with('success', 'Course Attendance deleted successfully');
    }
}
