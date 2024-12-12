<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceScheduleController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::get('unauthorized', function () {
    return view('pages.error.error-403', ['type_menu' => 'unauthorized']);
})->name('unauthorized');

// Route::middleware(['auth', AdminMiddleware::class])->group(function () {
//     Route::get('home', function () {
//         return view('pages.dashboard', ['type_menu' => 'home']);
//     })->name('home');

//     Route::resource('users', UserController::class);
//     Route::resource('courses', CourseController::class);
//     Route::resource('classrooms', ClassroomController::class);
//     Route::resource('classes', ClassesController::class);
//     Route::resource('attendances', AttendanceController::class);
//     Route::resource('permissions', PermissionController::class);
//     Route::resource('groups', GroupClassController::class);
//     Route::resource('attendance-schedules', AttendanceScheduleController::class);
//     Route::post('/attendance-schedules/toggle-status', [AttendanceScheduleController::class, 'toggleStatus'])->name('attendance-schedules.toggleStatus');
//     Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
// });

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('home', [DashboardController::class, 'index'])->name('home'); // Memanggil DashboardController@index

    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('groups', GroupClassController::class);
    Route::resource('attendance-schedules', AttendanceScheduleController::class);
    Route::post('/attendance-schedules/toggle-status', [AttendanceScheduleController::class, 'toggleStatus'])->name('attendance-schedules.toggleStatus');
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::resource('schedules', ScheduleController::class)->except(['show']);
});
