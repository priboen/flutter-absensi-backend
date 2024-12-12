<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'classroom_id',
        'courses_code',
        'name',
        // 'time_in',
        'credits',
    ];

    public function class()
    {
        return $this->hasOne(Classes::class, 'course_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function courseattendances()
    {
        return $this->hasMany(AttendanceSchedule::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
