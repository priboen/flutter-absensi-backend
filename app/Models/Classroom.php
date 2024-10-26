<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'building_name',
        'latitude',
        'longitude'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
