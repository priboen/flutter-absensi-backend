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
}
