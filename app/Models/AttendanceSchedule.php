<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'groupClass_id',
        'date',
        'time_start',
        'time_end',
        'is_open',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function groupClass()
    {
        return $this->belongsTo(GroupClasses::class, 'groupClass_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    public function isOpen()
    {
        return $this->is_open;
    }
}
