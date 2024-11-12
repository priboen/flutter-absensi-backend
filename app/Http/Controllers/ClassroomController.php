<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function index()
    {
        $classroom = Classroom::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
            activity()->causedBy(Auth::user())->log('Menampilkan halaman ruang kelas');
        return view('pages.classrooms.index', compact('classroom'));
    }
    public function create()
    {
        return view('pages.classrooms.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Classroom::create([
            'name' => $request->name,
            'building_name' => $request->building_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        activity()->causedBy(Auth::user())->log('Created new classroom: ' . $request->name);
        return redirect()->route('classrooms.index')->with('success', 'Classroom created successfully');
    }
    public function edit(Classroom $classroom)
    {
        return view('pages.classrooms.edit', compact('classroom'));
    }
    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $classroom->update([
            'name' => $request->name,
            'building_name' => $request->building_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        
        return redirect()->route('classrooms.index')->with('success', 'Classroom updated successfully');
    }
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')->with('success', 'Classroom deleted successfully');
    }
}
