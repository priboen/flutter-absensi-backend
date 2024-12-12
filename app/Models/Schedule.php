<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'day',
        'course_id',
        'groupClass_id',
        'time_in',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function groupClass()
    {
        return $this->belongsTo(GroupClasses::class, 'groupClass_id');
    }
    public function getTimeOutAttribute()
    {
        if (!$this->time_in || !$this->course->credits) {
            return null;
        }
        $timeIn = strtotime($this->time_in);
        $durationMinutes = $this->course->credits * 50;
        return date('H:i', strtotime("+$durationMinutes minutes", $timeIn));
    }
}
