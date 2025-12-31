<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];
    protected $casts = [
        'tags' => 'array',
        'departments' => 'array',
    ];

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function eventDates()
    {
        return $this->hasMany(EventDate::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'event_registrations')->withPivot('status')->withTimestamps();
    }
}
