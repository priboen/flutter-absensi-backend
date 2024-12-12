<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'groupClass_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    public function groupClass()
    {
        return $this->belongsTo(GroupClasses::class, 'groupClass_id');
    }
}
