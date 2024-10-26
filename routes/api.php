<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\AttendanceController;
use App\Http\Controllers\api\ClassesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::get('/courses', [ClassesController::class, 'getCoursesByStudent'])->middleware('auth:sanctum');

Route::post('/checkin', [AttendanceController::class, 'checkin'])->middleware('auth:sanctum');
Route::get('/is-checkin', [AttendanceController::class, 'isCheckedin'])->middleware('auth:sanctum');
Route::post('/checkout', [AttendanceController::class, 'checkout'])->middleware('auth:sanctum');
