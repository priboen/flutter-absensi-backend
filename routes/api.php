<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\AttendanceController;
use App\Http\Controllers\api\ClassesController;
use App\Http\Controllers\api\NoteController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\ScheduleController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::put('/update-user-profile', [ProfileController::class, 'updateUserProfile'])->middleware('auth:sanctum', AdminMiddleware::class);
Route::get('/user-profile', [ProfileController::class, 'getUserProfile'])->middleware('auth:sanctum');
Route::post('/password', [ProfileController::class, 'updateUserPassword'])->middleware('auth:sanctum');

Route::get('/courses', [ClassesController::class, 'getCoursesByStudent'])->middleware('auth:sanctum');

Route::post('/checkin', [AttendanceController::class, 'checkin'])->middleware('auth:sanctum');
Route::get('/is-checkin', [AttendanceController::class, 'isCheckedin'])->middleware('auth:sanctum');
Route::post('/checkout', [AttendanceController::class, 'checkout'])->middleware('auth:sanctum');
Route::apiResource('/permission', PermissionController::class)->middleware('auth:sanctum');
Route::apiResource('/note', NoteController::class)->middleware('auth:sanctum');
Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken'])->middleware('auth:sanctum');
Route::get('/schedule', [ScheduleController::class, 'getScheduleByUser'])->middleware('auth:sanctum');
Route::get('/today-schedule', [ScheduleController::class, 'getTodayUserSchedule'])->middleware('auth:sanctum');

Route::get('/attendance-history', [AttendanceController::class, 'index'])->middleware('auth:sanctum');
