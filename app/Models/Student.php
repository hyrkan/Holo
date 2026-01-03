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

    public function getFullNameAttribute()
    {
        return "{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : "") . "{$this->last_name}";
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

    public function isEligibleForCertificate(Event $event)
    {
        $registration = \App\Models\EventRegistration::where('event_id', $event->id)
            ->where('student_id', $this->id)
            ->first();
            
        return $registration && $registration->is_eligible_for_certificate;
    }
}
