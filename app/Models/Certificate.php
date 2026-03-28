<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getBackgroundImageUrlAttribute()
    {
        return $this->getImageUrl('background_image');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function signatories()
    {
        return $this->hasMany(CertificateSignatory::class)->orderBy('order');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'certificate_student');
    }
}
