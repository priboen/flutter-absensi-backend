<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupClasses extends Model
{
    protected $fillable = [
        'name',
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    public function attendanceSchedules()
    {
        return $this->hasMany(AttendanceSchedule::class);
    }
}
