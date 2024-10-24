<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['class', 'classroom']);

        // $query->when($request->has('name'), function ($q) use ($request) {
        //     $q->whereHas('user', function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->name . '%')
        //             ->orWhere('unique_number', 'like', '%' . $request->name . '%');
        //     });
        // });

        // $query->when($request->has('course'), function ($q) use ($request) {
        //     $q->whereHas('class.course', function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->course . '%');
        //     });
        // });

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
        return view('pages.attendances.index', compact('attendances'));
    }
}
