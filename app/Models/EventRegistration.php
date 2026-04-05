<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $guarded = [];

    protected $fillable = ['event_id', 'student_id', 'status', 'uuid'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($registration) {
            $registration->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
