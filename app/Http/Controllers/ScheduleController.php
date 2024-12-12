<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\GroupClasses;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $schedule = Schedule::with(['course', 'groupClass'])
            ->when($search, function ($query) use ($search) {
                $query->where('day', 'like', '%' . $search . '%')
                    ->orWhereHas('course', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('time_in', 'asc')
            ->paginate(10);

        return view('pages.schedules.index', compact('schedule', 'search'));
    }



    public function create()
    {
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.schedules.create', compact('course', 'groupClass'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'groupClass_id' => 'required|exists:group_classes,id',
                'day' => 'required',
            ]);

            Schedule::create([
                'course_id' => $request->course_id,
                'groupClass_id' => $request->groupClass_id,
                'day' => $request->day,
                'time_in' => $request->time_in,
            ]);

            return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('schedules.index')->with('error', 'Failed to create schedule. Please try again.' . $e->getMessage());
        }
    }
    public function edit(Schedule $schedule)
    {
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.schedules.edit', compact(['schedule', 'course', 'groupClass']));
    }
    public function update(Request $request, Schedule $schedule)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'groupClass_id' => 'required|exists:group_classes,id',
                'day' => 'required',
            ]);

            $schedule->update([
                'course_id' => $request->course_id,
                'day' => $request->day,
                'time_in' => $request->time_in,
                'groupClass_id' => $request->groupClass_id,
            ]);

            return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('schedules.index')->with('error', 'Failed to update schedule. Please try again.' . $e->getMessage());
        }
    }
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
