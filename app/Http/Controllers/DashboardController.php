<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudent = User::where('role', 'mahasiswa')->count();
        $totalCourse = Course::count();
        $permitNeedReview = Permission::whereNull('is_approved')->count();
        $totalClassroom = Classroom::count();

        $recentActivities = Activity::latest()->paginate(4);

        return view('pages.dashboard', compact('totalStudent', 'totalCourse', 'permitNeedReview', 'totalClassroom', 'recentActivities'));
    }
}
