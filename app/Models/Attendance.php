<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getPhotoUrlAttribute()
    {
        return $this->getImageUrl('photo');
    }

    protected $casts = [
        'scanned_at' => 'datetime',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function eventDate()
    {
        return $this->belongsTo(EventDate::class);
    }
}
