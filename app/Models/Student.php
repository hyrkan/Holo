<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'program',
        'year_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
