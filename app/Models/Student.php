<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'program',
        'year_level',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            $student->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_registrations')->withPivot('status')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
