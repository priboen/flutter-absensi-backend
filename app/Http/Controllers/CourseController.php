<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        activity()->causedBy(Auth::user())->log('Menampilkan halaman mata kuliah');
        return view('pages.courses.index', compact('course'));
    }
    public function create()
    {
        $classrooms = Classroom::all();
        return view('pages.courses.create', compact('classrooms'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'courses_code' => 'required|unique:courses,courses_code',
                'name' => 'required',
            ]);

            Course::create([
                'classroom_id' => $request->classroom_id,
                'courses_code' => $request->courses_code,
                'name' => $request->name,
                'semester' => $request->semester,
                // 'time_in' => $request->time_in,
                'credits' => $request->credits,
            ]);

            activity()->causedBy(Auth::user())->log('Created new course: ' . $request->name);
            return redirect()->route('courses.index')->with('success', 'Course created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Failed to create course. Please try again.' . $e->getMessage());
        }
    }
    public function edit(Course $course)
    {
        $classrooms = Classroom::all();
        return view('pages.courses.edit', compact(['course', 'classrooms']));
    }
    public function update(Request $request, Course $course)
    {
        try {
            $oldData = $course->getOriginal();
            $newData = $request->all();

            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'courses_code' => 'required|unique:courses,courses_code,' . $course->id,
                'name' => 'required',
            ]);

            $course->update([
                'classroom_id' => $request->classroom_id,
                'courses_code' => $request->courses_code,
                'name' => $request->name,
                'semester' => $request->semester,
                // 'time_in' => $request->time_in,
                'credits' => $request->credits,
            ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($course)
                ->withProperties(['old_data' => $oldData, 'new_data' => $newData])
                ->log('Updated course details: ' . $course->name);

            return redirect()->route('courses.index')->with('success', 'Course updated successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Failed to update course. Please try again.' . $e->getMessage());
        }
    }
    public function destroy(Course $course)
    {
        $course->delete();
        activity()->causedBy(Auth::user())->log('Deleted course: ' . $course->name);
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
    }
}
