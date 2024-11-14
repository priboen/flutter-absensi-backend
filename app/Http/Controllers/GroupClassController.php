<?php

namespace App\Http\Controllers;

use App\Models\GroupClass;
use App\Models\GroupClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupClassController extends Controller
{
    public function index()
    {
        $group = GroupClasses::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);
        activity()->causedBy(Auth::user())->log('Menampilkan halaman group class');
        return view('pages.groups.index', compact('group'));
    }
    public function create()
    {
        return view('pages.groups.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        GroupClasses::create([
            'name' => $request->name,
        ]);
        activity()
            ->causedBy(Auth::user())
            ->log('created group class ' . $request->name);
        return redirect()->route('groups.index')->with('success', 'Group Class created successfully');
    }
    public function destroy(GroupClasses $group)
    {
        $group->delete();
        activity()
            ->causedBy(Auth::user())
            ->log('deleted group class ' . $group->name);
        return redirect()->route('groups.index')->with('success', 'Classroom deleted successfully');
    }
}
