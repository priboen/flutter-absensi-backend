<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.courses.index', compact('course'));
    }
    public function create()
    {
        return view('pages.courses.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'courses_code' => 'required|unique:courses,courses_code',
            'name' => 'required',
        ]);
        Course::create([
            'courses_code' => $request->courses_code,
            'name' => $request->name,
            'semester' => $request->semester,
            'time_in' => $request->time_in,
            'credits' => $request->credits,
        ]);
        return redirect()->route('courses.index')->with('success', 'Course created successfully');
    }
    public function edit(Course $course)
    {
        return view('pages.courses.edit', compact('course'));
    }
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'courses_code' => 'required|unique:courses,courses_code,' . $course->id,
            'name' => 'required',
        ]);
        $course->update([
            'courses_code' => $request->courses_code,
            'name' => $request->name,
            'semester' => $request->semester,
            'time_in' => $request->time_in,
            'credits' => $request->credits,
        ]);
        if ($request->password) {
            $course->update([
                'courses_code' => $request->courses_code,
            ]);
        }
        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
    }
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
    }
}
