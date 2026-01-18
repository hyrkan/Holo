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
        'status',
        'student_type',
        'approved_at',
        'expired_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';
    const STATUS_EXPIRED = 'expired';

    const TYPE_REGULAR = 'regular';
    const TYPE_GUEST = 'guest';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            $student->uuid = (string) \Illuminate\Support\Str::uuid();
            if (!$student->status) {
                $student->status = self::STATUS_PENDING;
            }
        });
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isExpired()
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }

        if ($this->student_type === self::TYPE_GUEST && $this->expired_at && $this->expired_at->isPast()) {
            $this->update(['status' => self::STATUS_EXPIRED]);
            return true;
        }

        return false;
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
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

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'certificate_student');
    }
}
