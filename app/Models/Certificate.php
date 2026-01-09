<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $guarded = [];

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
