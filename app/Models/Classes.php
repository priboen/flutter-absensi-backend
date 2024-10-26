<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'course_id',
        'user_id'
    ];

    // Relasi One-to-One dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One-to-One dengan Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
