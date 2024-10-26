<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'class_id',
        'date_permission',
        'reason',
        'image',
        'is_approved',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
