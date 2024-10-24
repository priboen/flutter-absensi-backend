<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = [
        'class_id',
        'classroom_id',
        'date',
        'time_in',
        'time_out',
        'latlong_in',
        'latlon_out',
    ];
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
