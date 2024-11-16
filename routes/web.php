<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceScheduleController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('home', function () {
        return view('pages.dashboard', ['type_menu' => 'home']);
    })->name('home');

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


    // Route::middleware([RoleMiddleware::class . 'admin'])->group(function () {});
});
