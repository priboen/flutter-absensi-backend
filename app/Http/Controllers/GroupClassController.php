<?php

namespace App\Http\Controllers;

use App\Models\GroupClass;
use App\Models\GroupClasses;
use Illuminate\Http\Request;

class GroupClassController extends Controller
{
    public function index()
    {
        $group = GroupClasses::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('pages.groups.index', compact('group'));
    }
    // public function create()
    // {
    //     return view('pages.groups.create');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        GroupClasses::create([
            'name' => $request->name,
        ]);
        return redirect()->route('groups.index')->with('success', 'Group Class created successfully');
    }
    public function destroy(GroupClasses $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Classroom deleted successfully');
    }
}
