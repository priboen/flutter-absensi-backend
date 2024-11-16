<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Course;
use App\Models\GroupClasses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $query = Classes::with(['course', 'user'])->orderBy('id', 'asc');
        if ($request->has('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%')
                    ->orwhere('unique_number', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->has('course')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->course . '%');
            });
        }

        $classes = $query->orderBy('id', 'asc')->paginate(10);
        activity()->causedBy(Auth::user())->log('Menampilkan halaman rencana studi');
        return view('pages.classes.index', compact('classes'));
    }
    public function create()
    {
        $user = User::all();
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.classes.create', compact(['user', 'course', 'groupClass']));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'groupClass_id' => 'required|exists:group_classes,id',
        ]);
        Classes::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'groupClass_id' => $request->groupClass_id,
        ]);
        activity()->causedBy(Auth::user())->log('Created new KRS' . $request->name);
        return redirect()->route('classes.index')->with('success', 'KRS created successfully');
    }
    public function edit(Classes $class)
    {
        $user = User::all();
        $course = Course::all();
        $groupClass = GroupClasses::all();
        return view('pages.classes.edit', compact(['class', 'user', 'course', 'groupClass']));
    }
    public function update(Request $request, Classes $class)
    {
        $oldData = $class->getOriginal();
        $newData = $request->all();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'groupClass_id' => 'required',
        ]);
        $class->update($request->all());
        activity()
            ->causedBy(Auth::user())
            ->performedOn($class)
            ->withProperties(['old_data' => $oldData, 'new_data' => $newData])
            ->log('Updated user details: ' . $class->name);

        return redirect()->route('classes.index')->with('success', 'KRS updated successfully');
    }
    public function destroy(Classes $class)
    {
        $class->delete();
        activity()->causedBy(Auth::user())->log('Deleted KRS: ' . $class->name);
        return redirect()->route('classes.index')->with('success', 'KRS deleted successfully');
    }
}
