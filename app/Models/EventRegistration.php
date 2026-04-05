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
            if (!$registration->uuid) {
                $registration->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        // Fail-safe for existing records that might be missing a UUID
        static::retrieved(function ($registration) {
            if (!$registration->uuid) {
                $registration->uuid = (string) \Illuminate\Support\Str::uuid();
                $registration->unsetEventDispatcher();
                $registration->save(['timestamps' => false]);
            }
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
