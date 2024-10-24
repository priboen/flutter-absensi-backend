<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'courses_code',
        'name',
        'time_in',
        'credits',
    ];

    public function class()
    {
        return $this->hasOne(Classes::class, 'course_id', 'id');
    }
}
